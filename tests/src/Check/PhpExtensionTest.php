<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Evolver\DiagnosticsTest\Check;

use Evolver\Diagnostics\Check\PhpExtension;

/**
 * PHP extension check test
 *
 * @package Evolver\DiagnosticsTest\Check
 */
class PhpExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider loadedExtensionsDataProvider
     *
     * @covers       \Evolver\Diagnostics\Config\Check::__construct()
     *
     * @param string $name
     * @param bool|string $version
     */
    public function testPhpExtensionCheck($name, $version)
    {
        $check = new PhpExtension($name, $version);
        $this->assertInstanceOf('ZendDiagnostics\Result\Success', $check->check());
    }

    /**
     * Provide data about all loaded extensions
     *
     * @return array
     */
    public function loadedExtensionsDataProvider()
    {
        $data = array();
        foreach (get_loaded_extensions() as $name) {
            $arguments = array($name);
            $version = phpversion($name);
            if (!is_string($version)) {
                $version = true;
            }
            $arguments[] = $version;
            $data[] = $arguments;
        }
        return $data;
    }
}
