<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Evolver\DiagnosticsTest\Console\Command;

use Evolver\Diagnostics\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Yaml\Yaml;

/**
 * Check command test
 *
 * @package Evolver\DiagnosticsTest\Consiole\Command
 */
class CheckCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $config;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->config = tempnam(sys_get_temp_dir(), 'yml');
        file_put_contents($this->config, Yaml::dump(array(
            'checks' => array()
        )));
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        unlink($this->config);
    }

    /**
     * @covers \Evolver\Diagnostics\Console\Command\CheckCommand::execute()
     */
    public function testExecution()
    {
        $application = new Application();
        $command = $application->find('check');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute(array(
            'command' => $command->getName(),
            'config' => $this->config
        ));

        $this->assertEquals(0, $exitCode);
        $this->assertContains(strip_tags($application->getLongVersion()), $commandTester->getDisplay());
        $this->assertContains(sprintf('Configuration read from "%s"', $this->config), $commandTester->getDisplay());
    }
}
