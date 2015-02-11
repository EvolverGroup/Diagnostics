<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Evolver\Diagnostics\Console;

use Evolver\Diagnostics\Console\Command\CheckCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ZendDiagnostics\Runner\Runner;

/**
 * Diagnostics console application
 *
 * @package Evolver\Diagnostics\Console
 */
class Application extends \Symfony\Component\Console\Application
{
    /**
     * Application name
     */
    const NAME = 'Evolver diagnostics application';

    /**
     * Application version
     */
    const VERSION = '0.0.1';

    /**
     * Diagnostics runner instance
     *
     * @var Runner
     */
    protected $runner;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct(self::NAME, self::VERSION);
        $this->runner = new Runner();
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultCommands()
    {
        return array_merge(
            parent::getDefaultCommands(),
            array(
                new CheckCommand()
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->getRunner()->addReporter(new Reporter($output));
        return parent::doRun($input, $output);
    }

    /**
     * Get the diagnostics runner instance
     *
     * @return Runner
     */
    public function getRunner()
    {
        return $this->runner;
    }
}
