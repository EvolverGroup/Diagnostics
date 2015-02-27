<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@evolver.de>
 */

namespace Evolver\DiagnosticsTest\Console;

use Evolver\Diagnostics\Console\Application;

/**
 * Diagnostics console application test
 *
 * @package Evolver\DiagnosticsTest\Consiole
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Evolver\Diagnostics\Console\Application::getRunner()
     */
    public function testApplicationRunner()
    {
        $application = new Application();

        $this->assertInstanceOf('ZendDiagnostics\Runner\Runner', $application->getRunner());
    }
}
