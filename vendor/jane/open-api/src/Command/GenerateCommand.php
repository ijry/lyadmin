<?php

namespace Joli\Jane\OpenApi\Command;

use Joli\Jane\OpenApi\JaneOpenApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('generate');
        $this->setDescription('Generate an api client: class, normalizers and resources given a specific Json OpenApi file');
        $this->addOption('config-file', 'c', InputOption::VALUE_OPTIONAL, 'File to use for Jane OpenAPI configuration', '.jane-openapi');
        $this->addOption('reference', null, InputOption::VALUE_NONE, 'Use the JSON Reference specification in your generated library');
        $this->addOption('date-format', 'd', InputOption::VALUE_OPTIONAL, 'Date time format to use for date time field');
        $this->addArgument('openapi-file', InputArgument::OPTIONAL, 'Location of the OpenApi (Swagger) Schema file');
        $this->addArgument('namespace', InputArgument::OPTIONAL, 'Namespace prefix to use for generated files');
        $this->addArgument('directory', InputArgument::OPTIONAL, 'Directory where to generate files');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $options = [];

        if ($input->hasOption('config-file')) {
            $configFile = $input->getOption('config-file');

            if (!file_exists($configFile)) {
                throw new \RuntimeException(sprintf('Config file %s does not exist', $configFile));
            }

            $options = require $configFile;

            if (!is_array($options)) {
                throw new \RuntimeException(sprintf('Invalid config file specified or invalid return type in file %s', $configFile));
            }
        } else {
            if ($input->hasArgument('openapi-file') && null !== $input->getArgument('openapi-file')) {
                $options['openapi-file'] = $input->getArgument('openapi-file');
            }

            if ($input->hasArgument('namespace') && null !== $input->getArgument('namespace')) {
                $options['namespace'] = $input->getArgument('namespace');
            }

            if ($input->hasArgument('directory') && null !== $input->getArgument('directory')) {
                $options['directory'] = $input->getArgument('directory');
            }

            if ($input->hasOption('date-format') && null !== $input->getOption('date-format')) {
                $options['date-format'] = $input->getOption('date-format');
            }

            if ($input->hasOption('no-reference') && null !== $input->getOption('no-reference')) {
                $options['reference'] = $input->getOption('reference');
            }
        }

        $options = $this->resolveConfiguration($options);

        $janeOpenApi = JaneOpenApi::build($options);
        $files       = $janeOpenApi->generate($options['openapi-file'], $options['namespace'], $options['directory']);
        $janeOpenApi->printFiles($files, $options['directory']);

        foreach ($files as $file) {
            $output->writeln(sprintf("Generate %s", $file->getFilename()));
        }
    }

    protected function resolveConfiguration(array $options = [])
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults([
            'reference' => false,
            'date-format' => \DateTime::RFC3339,
        ]);

        $optionsResolver->setRequired([
            'openapi-file',
            'namespace',
            'directory',
        ]);

        return $optionsResolver->resolve($options);
    }
}
