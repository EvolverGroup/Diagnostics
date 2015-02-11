<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Evolver\DiagnosticsTest;

use Evolver\Diagnostics\DiagnosticsConfig;
use Symfony\Component\Yaml\Yaml;

/**
 * Config test
 *
 * @package Evolver\Diagnostics
 */
abstract class AbstractChecksConfigAwareTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Get checks config
     *
     * @return array
     */
    public function getChecksConfig()
    {
        $extensionsLoaded = get_loaded_extensions();
        $streamWrappers = stream_get_wrappers();
        $phpFlags = array();
        foreach (ini_get_all(null, false) as $name => $value) {
            $phpFlags[] = array($name, $value);
        }

        $randomLoadedExtension = array_rand($extensionsLoaded);
        $randomStreamWrapper = array_rand($streamWrappers);
        $randomPhpFlag = array_rand($phpFlags);

        return array(
            array(
                'name' => 'ApcFragmentation',
                'arguments' => array(50, 75)
            ),
            array(
                'name' => 'ApcMemory'
            ),
            array(
                'name' => 'Callback',
                'arguments' => array(function () {
                })
            ),
            array(
                'name' => 'ClassExists',
                'arguments' => array('stdClass')
            ),
            array(
                'name' => 'CpuPerformance',
            ),
            array(
                'name' => 'DirReadable',
                'arguments' => array(sys_get_temp_dir())
            ),
            array(
                'name' => 'DirWritable',
                'arguments' => array(sys_get_temp_dir())
            ),
            array(
                'name' => 'DiskFree',
                'arguments' => array(0)
            ),
            array(
                'name' => 'DiskUsage',
                'arguments' => array(50, 75)
            ),
            array(
                'name' => 'ExtensionLoaded',
                'arguments' => array($randomLoadedExtension)
            ),
            array(
                'name' => 'GuzzleHttpService',
                'arguments' => array('localhost')
            ),
            array(
                'name' => 'HttpService',
                'arguments' => array('localhost')
            ),
            array(
                'name' => 'IniFile',
                'arguments' => array('/path/to/file.ini')
            ),
            array(
                'name' => 'JsonFile',
                'arguments' => array('/path/to/file.json')
            ),
            array(
                'name' => 'Memcache',
                'arguments' => array('localhost')
            ),
            array(
                'name' => 'OpCacheMemory',
                'arguments' => array(50, 75)
            ),
            array(
                'name' => 'PhpExtension',
                'arguments' => array($randomLoadedExtension)
            ),
            array(
                'name' => 'PhpFlag',
                'arguments' => array($randomPhpFlag[0], $randomPhpFlag[1])
            ),
            array(
                'name' => 'PhpVersion',
                'arguments' => array(PHP_VERSION)
            ),
            array(
                'name' => 'ProcessRunning',
                'arguments' => array(getmypid())
            ),
            array(
                'name' => 'RabbitMQ',
            ),
            array(
                'name' => 'Redis',
            ),
            array(
                'name' => 'SecurityAdvisory',
            ),
            array(
                'name' => 'StreamWrapperExists',
                'arguments' => array($randomStreamWrapper)
            ),
            array(
                'name' => 'XmlFile',
                'arguments' => array('/path/to/file.xml')
            ),
            array(
                'name' => 'YamlFile',
                'arguments' => array('/path/to/file.yml')
            )
        );
    }
}
