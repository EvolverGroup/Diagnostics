<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@evolver.de>
 */

namespace Evolver\Diagnostics\Console\Command;

use Evolver\Diagnostics\Config;
use Evolver\Diagnostics\Console\Application;
use Evolver\Diagnostics\Util\ClassLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZendDiagnostics\Check\CheckInterface;

/**
 * Check command
 *
 * @package Evolver\Diagnostics\Command
 */
class CheckCommand extends Command
{
    /**
     * Default config
     */
    const DEFAULT_CONFIG = 'diagnostics.yml';

    /**
     * Check namespaces
     *
     * @var array
     */
    public static $checkNamespaces = array(
        'Evolver\Diagnostics\Check',
        'ZendDiagnostics\Check'
    );

    /**
     * @var ClassLocator
     */
    private $classLocator;

    /**
     * Get application instance
     *
     * @return \Evolver\Diagnostics\Console\Application
     */
    public function getApplication()
    {
        $application = parent::getApplication();
        if (!$application instanceof Application) {
            throw new \LogicException('This command can only be added to a diagnostics application.');
        }
        return $application;
    }

    /**
     * Get class locator
     *
     * @return ClassLocator
     */
    public function getClassLocator()
    {
        if (null === $this->classLocator) {
            $this->classLocator = new ClassLocator(static::$checkNamespaces);
        }
        return $this->classLocator;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('check')
            ->setDescription('Make diagnostic checks')
            ->addOption(
                'alias',
                null,
                InputOption::VALUE_REQUIRED,
                'An alias of a check to run'
            )
            ->addArgument(
                'config',
                InputArgument::OPTIONAL,
                'The diagnostic config file path',
                self::DEFAULT_CONFIG
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (OutputInterface::VERBOSITY_QUIET !== $output->getVerbosity()) {
            $output->writeln($this->getApplication()->getLongVersion() . PHP_EOL);
        }

        $configFile = new \SplFileInfo($input->getArgument('config'));
        if (!$configFile->isFile()) {
            $output->writeln(
                sprintf('<error>Unable to read diagnostics config file "%s".</error>', $configFile)
            );
            return 1;
        }

        $diagnosticsConfig = Config\Diagnostics::fromYaml($configFile);
        if (OutputInterface::VERBOSITY_QUIET !== $output->getVerbosity()) {
            $output->writeln(sprintf('Configuration read from "%s"', $configFile->getRealPath()) . PHP_EOL);
        }

        foreach ($diagnosticsConfig->getChecks() as $checkConfig) {
            if (!$checkConfig instanceof Config\Check) {
                continue;
            }

            $instance = $this->getClassLocator()->createInstance($checkConfig->getName(), $checkConfig->getArguments());
            if (!$instance instanceof CheckInterface) {
                throw new \RuntimeException(sprintf(
                    'Object "%s" must be instance of ZendDiagnostics\Check\CheckInterface.',
                    $checkConfig['name']
                ));
            }

            $this->getApplication()->getRunner()->addCheck($instance, $checkConfig->getAlias());
        }

        $results = $this->getApplication()->getRunner()->run($input->getOption('alias'));
        if (count($results) > $results->getSuccessCount()) {
            return 1;
        }
        return 0;
    }
}
