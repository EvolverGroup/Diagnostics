<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Evolver\Diagnostics\Console;

use ArrayObject;
use Symfony\Component\Console\Output\OutputInterface;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Collection as ResultsCollection;
use ZendDiagnostics\Result\FailureInterface;
use ZendDiagnostics\Result\ResultInterface;
use ZendDiagnostics\Result\SkipInterface;
use ZendDiagnostics\Result\SuccessInterface;
use ZendDiagnostics\Result\WarningInterface;
use ZendDiagnostics\Runner\Reporter\ReporterInterface;

/**
 * Diagnostics console reporter
 *
 * @package Evolver\Diagnostics\Console
 */
class Reporter implements ReporterInterface
{
    /**
     * Output
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * Options
     *
     * @var array
     */
    protected $options;

    /**
     * The timestamp indicating when the runner has been started
     *
     * @var float
     */
    protected $timestamp;

    /**
     * Has the Runner been stopped
     *
     * @var bool
     */
    protected $stopped;

    /**
     * Create new reporter
     *
     * @param OutputInterface $output
     * @param array $options
     */
    public function __construct(OutputInterface $output, array $options = array())
    {
        $this->output = $output;
        $this->options = $options;
        $this->timestamp = 0.0;
        $this->stopped = false;
    }

    /**
     * Get output instance
     *
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Get option with specific name
     *
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        if (!isset($this->options[$name])) {
            return $default;
        }
        return $this->options[$name];
    }

    /**
     * Get memory usage in megabytes
     *
     * @return string
     */
    public function getMemoryUsage()
    {
        return sprintf('%4.2f', memory_get_peak_usage(true) / 1048576);
    }

    /**
     * Get time since start in seconds
     *
     * @return string
     */
    public function getTimeSinceStart()
    {
        $time = $this->timestamp;
        if ($time > 0) {
            $time = microtime(true) - $time;
        }
        return sprintf('%.2f', $time);
    }

    /**
     * @inheritdoc
     */
    public function onStart(ArrayObject $checks, $runnerConfig)
    {
        $this->timestamp = microtime(true);
        $this->stopped = false;
    }

    /**
     * @inheritdoc
     */
    public function onBeforeRun(CheckInterface $check, $checkAlias = null)
    {
    }

    /**
     * @inheritdoc
     */
    public function onAfterRun(CheckInterface $check, ResultInterface $result, $checkAlias = null)
    {
        $output = $this->getOutput();
        if ($result instanceof SuccessInterface) {
            $output->write('.');
        } elseif ($result instanceof FailureInterface) {
            $output->write('<fg=white;bg=red>F</fg=white;bg=red>');
        } elseif ($result instanceof WarningInterface) {
            $output->write('<fg=yellow>W</fg=yellow>');
        } elseif ($result instanceof SkipInterface) {
            $output->write('<fg=cyan>S</fg=cyan>');
        } else {
            $output->write('<fg=magenta>U</fg=magenta>');
        }
    }

    /**
     * @inheritdoc
     */
    public function onStop(ResultsCollection $results)
    {
        $this->stopped = true;
    }

    /**
     * @inheritdoc
     */
    public function onFinish(ResultsCollection $results)
    {
        $this->getOutput()->writeln(
            PHP_EOL . PHP_EOL .
            sprintf('Time: %s seconds, Memory: %sMb', $this->getTimeSinceStart(), $this->getMemoryUsage()) . PHP_EOL
        );

        $this->writeResultsOutput($results);
        $this->writeSummaryOutput($results);
    }

    /**
     * Write results output
     *
     * @param ResultsCollection $results
     */
    protected function writeResultsOutput(ResultsCollection $results)
    {
        $output = $this->getOutput();
        foreach ($results as $check) {
            /** @var ResultInterface $result */
            $result = $results->offsetGet($check);
            if ($result instanceof SuccessInterface) {
                continue;
            }

            if ($result instanceof FailureInterface) {
                $output->writeln(sprintf('Failure: %s', $check->getLabel()));
            } elseif ($result instanceof WarningInterface) {
                $output->writeln(sprintf('Warning: %s', $check->getLabel()));
            } else {
                $output->writeln(sprintf('Unknown result "%s": %s', get_class($result), $check->getLabel()));
            }

            $message = $result->getMessage();
            if (!empty($message)) {
                $output->writeln($message);
            }

            $data = $result->getData();
            if (is_string($data) && !empty($data)) {
                $output->writeln($data);
            }

            $output->write(PHP_EOL);
        }
    }

    /**
     * Write summary output
     *
     * @param ResultsCollection $results
     */
    protected function writeSummaryOutput(ResultsCollection $results)
    {
        if ($this->stopped) {
            $this->getOutput()->writeln('Diagnostics aborted because of a failure.');
            return;
        }

        if (count($results) > $results->getSuccessCount()) {
            $buffer = array();

            $buffer[] = sprintf('Tests: %u', count($results));
            $buffer[] = sprintf('Failures: %u', $results->getFailureCount());

            if ($results->getWarningCount() > 0) {
                $buffer[] = sprintf('Warnings: %u', $results->getWarningCount());
            }

            if ($results->getSkipCount() > 0) {
                $buffer[] = sprintf('Skipped: %u', $results->getSkipCount());
            }

            if ($results->getUnknownCount() > 0) {
                $buffer[] = sprintf('Unknown results: %u', $results->getUnknownCount());
            }

            $summary = implode(', ', $buffer);
            $this->getOutput()->writeln(array(
                sprintf('<fg=white;bg=red>%-' . mb_strlen($summary) . 's</fg=white;bg=red>', 'FAILURE!'),
                sprintf('<fg=white;bg=red>%s</fg=white;bg=red>', $summary)
            ));
            return;
        }

        $summary = sprintf('OK (%u tests)', $results->getSuccessCount());
        $this->getOutput()->writeln(array(
            sprintf('%-' . mb_strlen($summary) . 's', 'SUCCESS!'),
            $summary
        ));
    }
}
