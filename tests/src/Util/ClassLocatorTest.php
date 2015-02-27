<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@evolver.de>
 */

namespace Evolver\DiagnosticsTest\Util;

use Evolver\Diagnostics\Util\ClassLocator;

/**
 * Class locator util test
 *
 * @package Evolver\DiagnosticsTest\Util
 */
class ClassLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Evolver\Diagnostics\Util\ClassLocator::createInstance()
     */
    public function testSuccessfulInstanceCreation()
    {
        $classLocator = new ClassLocator();

        $this->assertInstanceOf('stdClass', $classLocator->createInstance('stdClass'));
    }

    /**
     * @covers \Evolver\Diagnostics\Util\ClassLocator::createInstance()
     */
    public function testUnsuccessfulInstanceCreation()
    {
        $this->setExpectedException('RuntimeException');

        $classLocator = new ClassLocator();

        $classLocator->createInstance('thisClassNameShouldNeverExist');
    }
}
