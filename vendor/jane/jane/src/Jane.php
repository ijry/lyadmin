<?php

namespace Joli\Jane;

use Joli\Jane\Generator\Context\Context;
use Joli\Jane\Generator\ModelGenerator;
use Joli\Jane\Generator\Naming;
use Joli\Jane\Generator\NormalizerGenerator;
use Joli\Jane\Guesser\ChainGuesser;
use Joli\Jane\Guesser\JsonSchema\JsonSchemaGuesserFactory;
use Joli\Jane\Normalizer\NormalizerFactory;
use Joli\Jane\Runtime\Encoder\RawEncoder;
use PhpCsFixer\Differ\NullDiffer;
use PhpCsFixer\Error\ErrorsManager;
use PhpCsFixer\Linter\NullLinter;
use PhpCsFixer\Runner\Runner;
use PhpParser\PrettyPrinter\Standard;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use PhpCsFixer\Config;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Console\ConfigurationResolver;
use PhpCsFixer\Finder;

class Jane
{
    const VERSION = '1.0-dev';

    private $serializer;

    private $modelGenerator;

    private $normalizerGenerator;

    private $fixerConfig;

    private $chainGuesser;

    public function __construct(Serializer $serializer, ChainGuesser $chainGuesser, ModelGenerator $modelGenerator, NormalizerGenerator $normalizerGenerator, ConfigInterface $fixerConfig = null)
    {
        $this->serializer = $serializer;
        $this->chainGuesser = $chainGuesser;
        $this->modelGenerator = $modelGenerator;
        $this->normalizerGenerator = $normalizerGenerator;
        $this->fixerConfig = $fixerConfig;
    }

    /**
     * Return a list of class guessed.
     *
     * @param $schemaFilePath
     * @param $name
     * @param $namespace
     * @param $directory
     *
     * @return Context
     */
    public function createContext($schemaFilePath, $name, $namespace, $directory)
    {
        $schema = $this->serializer->deserialize(file_get_contents($schemaFilePath), 'Joli\Jane\Model\JsonSchema', 'json');
        $classes = $this->chainGuesser->guessClass($schema, $name);

        foreach ($classes as $class) {
            $properties = $this->chainGuesser->guessProperties($class->getObject(), $name, $classes);

            foreach ($properties as $property) {
                $property->setType($this->chainGuesser->guessType($property->getObject(), $property->getName(), $classes));
            }

            $class->setProperties($properties);
        }

        return new Context($schema, $namespace, $directory, $classes);
    }

    /**
     * Generate code.
     *
     * @param $schemaFilePath
     * @param $name
     * @param $namespace
     * @param $directory
     *
     * @return array
     */
    public function generate($schemaFilePath, $name, $namespace, $directory)
    {
        $context = $this->createContext($schemaFilePath, $name, $namespace, $directory);

        if (!file_exists(($directory.DIRECTORY_SEPARATOR.'Model'))) {
            mkdir($directory.DIRECTORY_SEPARATOR.'Model', 0755, true);
        }

        if (!file_exists(($directory.DIRECTORY_SEPARATOR.'Normalizer'))) {
            mkdir($directory.DIRECTORY_SEPARATOR.'Normalizer', 0755, true);
        }

        $prettyPrinter = new Standard();
        $modelFiles = $this->modelGenerator->generate($context->getRootReference(), $name, $context);
        $normalizerFiles = $this->normalizerGenerator->generate($context->getRootReference(), $name, $context);
        $generated = [];

        foreach ($modelFiles as $file) {
            $generated[] = $file->getFilename();
            file_put_contents($file->getFilename(), $prettyPrinter->prettyPrintFile([$file->getNode()]));
        }

        foreach ($normalizerFiles as $file) {
            $generated[] = $file->getFilename();
            file_put_contents($file->getFilename(), $prettyPrinter->prettyPrintFile([$file->getNode()]));
        }

        $this->fix($directory);

        return $generated;
    }

    /**
     * Fix files generated in a directory.
     *
     * @param $directory
     *
     * @return array|void
     */
    protected function fix($directory)
    {
        if (!class_exists('PhpCsFixer\Runner\Runner')) {
            return;
        }

        /** @var Config $fixerConfig */
        $fixerConfig = $this->fixerConfig;

        if (null === $fixerConfig) {
            $fixerConfig = Config::create()
                ->setRiskyAllowed(true)
                ->setRules(array(
                    '@Symfony' => true,
                    'simplified_null_return' => false,
                    'concat_without_spaces' => false,
                    'double_arrow_multiline_whitespaces' => false,
                    'unalign_equals' => false,
                    'unalign_double_arrow' => false,
                    'align_double_arrow' => true,
                    'align_equals' => true,
                    'concat_with_spaces' => true,
                    'ordered_imports' => true,
                    'phpdoc_order' => true,
                    'short_array_syntax' => true,
                ))
            ;

            $resolver = new ConfigurationResolver();

            if (method_exists($resolver, 'setFormats')) {
                $resolver->setFormats(['txt']);
            }

            $resolver->setDefaultConfig($fixerConfig);
            $resolver->resolve();
        }

        $finder = new Finder();
        $finder->in($directory);
        $fixerConfig->finder($finder);

        $fixer = new Runner(
            $fixerConfig,
            new NullDiffer(),
            null,
            new ErrorsManager(),
            new NullLinter(),
            false
        );

        return $fixer->fix();
    }

    public static function build($options = [])
    {
        $serializer = self::buildSerializer();
        $chainGuesser = JsonSchemaGuesserFactory::create($serializer, $options);
        $naming = new Naming();
        $modelGenerator = new ModelGenerator($naming, $chainGuesser, $chainGuesser);
        $normGenerator = new NormalizerGenerator($naming, isset($options['reference']) ? $options['reference'] : true);

        return new self($serializer, $chainGuesser, $modelGenerator, $normGenerator);
    }

    public static function buildSerializer()
    {
        $encoders = [new JsonEncoder(new JsonEncode(JSON_UNESCAPED_SLASHES), new JsonDecode(false)), new RawEncoder()];
        $normalizers = NormalizerFactory::create();

        return new Serializer($normalizers, $encoders);
    }
}
