<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Evolver\DiagnosticsTest\Console;

use Evolver\Diagnostics\Console\Reporter;

/**
 * Diagnostics console reporter test
 *
 * @package Evolver\DiagnosticsTest\Consiole
 */
class ReporterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->output = $this->getMock(
            'Symfony\Component\Console\Output\StreamOutput',
            array('write', 'writeln'),
            array(),
            '',
            false
        );

        $this->output->expects($this->any())
            ->method('write');
        $this->output->expects($this->any())
            ->method('writeln');
    }

    /**
     * @covers \Evolver\Diagnostics\Console\Reporter::getOutput()
     */
    public function testOutputGetter()
    {
        $reporter = new Reporter($this->output);

        $this->assertEquals($this->output, $reporter->getOutput());
    }

    /**
     * @covers \Evolver\Diagnostics\Console\Reporter::getOption()
     */
    public function testOptionGetter()
    {
        $reporter = new Reporter($this->output, array(
            'foo' => 'bar'
        ));

        $this->assertEquals(null, $reporter->getOption('baz'));
        $this->assertEquals(true, $reporter->getOption('baz', true));
        $this->assertEquals('bar', $reporter->getOption('foo'));
        $this->assertEquals('bar', $reporter->getOption('foo', true));
    }

    /**
     * @covers \Evolver\Diagnostics\Console\Reporter::getMemoryUsage()
     */
    public function testMemoryUsageGetter()
    {
        $reporter = new Reporter($this->output);

        $this->assertRegExp('/^\d{1,4}\.\d{1,2}$/', $reporter->getMemoryUsage());
    }

    /**
     * @covers \Evolver\Diagnostics\Console\Reporter::getTimeSinceStart()
     */
    public function testTimeSinceStartGetter()
    {
        $reporter = new Reporter($this->output);

        $this->assertRegExp('/^\d{1,}\.\d{1,2}$/', $reporter->getTimeSinceStart());
    }
}
