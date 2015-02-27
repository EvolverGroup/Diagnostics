<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@evolver.de>
 */

namespace Evolver\DiagnosticsTest\Config;

use Evolver\Diagnostics\Config\Check as CheckConfig;

/**
 * Check config test
 *
 * @package Evolver\DiagnosticsTest\Config
 */
class CheckTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Evolver\Diagnostics\Config\Check::__construct()
     */
    public function testCreation()
    {
        $this->assertInstanceOf('Evolver\Diagnostics\Config\Check', new CheckConfig());
    }

    /**
     * @covers \Evolver\Diagnostics\Config\Check::getName()
     * @covers \Evolver\Diagnostics\Config\Check::getArguments()
     * @covers \Evolver\Diagnostics\Config\Check::getAlias()
     */
    public function testGetters()
    {
        $expectedName = 'Test';
        $expectedArguments = array('foo', 1, true);
        $expectedAlias = 'my_test';

        $config = new CheckConfig(array(
            'name' => $expectedName,
            'arguments' => $expectedArguments,
            'alias' => $expectedAlias
        ));

        $this->assertEquals($expectedName, $config->getName());
        $this->assertEquals($expectedArguments, $config->getArguments());
        $this->assertEquals($expectedAlias, $config->getAlias());
    }

    /**
     * @expectedException \RuntimeException
     *
     * @covers \Evolver\Diagnostics\Config\Check::getName()
     */
    public function testNameGetterThrowsException()
    {
        $config = new CheckConfig();
        $config->getName();
    }
}
