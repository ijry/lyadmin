<?php

namespace Joli\Jane\Command;

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
        $this->setDescription('Generate a set of class and normalizers given a specific Json Schema file');
        $this->addOption('config-file', 'c', InputOption::VALUE_OPTIONAL, 'File to use for jane configuration');
        $this->addOption('no-reference', null, InputOption::VALUE_NONE, 'Don\'t use the reference system in your generated schema');
        $this->addOption('date-format', 'd', InputOption::VALUE_OPTIONAL, 'Date time format to use for date time field');
        $this->addArgument('json-schema-file', InputArgument::OPTIONAL, 'Location of the Json Schema file');
        $this->addArgument('root-class', InputArgument::OPTIONAL, 'Name of the root entity you want to generate');
        $this->addArgument('namespace', InputArgument::OPTIONAL, 'Namespace prefix to use for generated files');
        $this->addArgument('directory', InputArgument::OPTIONAL, 'Directory where to generate files');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $options = [];

        if ($input->hasOption('config-file') && null !== $input->getOption('config-file')) {
            $configFile = $input->getOption('config-file');

            if (!file_exists($configFile)) {
                throw new \RuntimeException(sprintf('Config file %s does not exist', $configFile));
            }

            $options = require $configFile;

            if (!is_array($options)) {
                throw new \RuntimeException(sprintf('Invalid config file specified or invalid return type in file %s', $configFile));
            }
        } else {
            if ($input->hasArgument('json-schema-file') && null !== $input->getArgument('json-schema-file')) {
                $options['json-schema-file'] = $input->getArgument('json-schema-file');
            }

            if ($input->hasArgument('root-class') && null !== $input->getArgument('root-class')) {
                $options['root-class'] = $input->getArgument('root-class');
            }

            if ($input->hasArgument('directory') && null !== $input->getArgument('directory')) {
                $options['directory'] = $input->getArgument('directory');
            }

            if ($input->hasArgument('namespace') && null !== $input->getArgument('namespace')) {
                $options['namespace'] = $input->getArgument('namespace');
            }

            if ($input->hasOption('date-format') && null !== $input->getOption('date-format')) {
                $options['date-format'] = $input->getOption('date-format');
            }

            if ($input->hasOption('no-reference') && null !== $input->getOption('no-reference')) {
                $options['reference'] = !$input->getOption('no-reference');
            }
        }

        $options = $this->resolveConfiguration($options);

        $jane    = \Joli\Jane\Jane::build($options);
        $files   = $jane->generate($options['json-schema-file'], $options['root-class'], $options['namespace'], $options['directory']);

        foreach ($files as $file) {
            $output->writeln(sprintf("Generated %s", $file));
        }
    }

    protected function resolveConfiguration(array $options = [])
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults([
            'reference' => true,
            'date-format' => \DateTime::RFC3339,
        ]);

        $optionsResolver->setRequired([
            'json-schema-file',
            'root-class',
            'namespace',
            'directory',
        ]);

        return $optionsResolver->resolve($options);
    }
}
