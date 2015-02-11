<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Evolver\DiagnosticsTest\Config;

use Evolver\Diagnostics\Config\Diagnostics as DiagnosticsConfig;
use Symfony\Component\Yaml\Yaml;

/**
 * Diagnostics config test
 *
 * @package Evolver\DiagnosticsTest\Config
 */
class DiagnosticsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->config = array(
            'checks' => array(
                array(
                    'name' => 'Test'
                )
            )
        );
    }

    /**
     * @covers \Evolver\Diagnostics\Config\Diagnostics::__construct()
     */
    public function testCreation()
    {
        $this->assertInstanceOf('Evolver\Diagnostics\Config\Diagnostics', new DiagnosticsConfig($this->config));
    }

    /**
     * @covers \Evolver\Diagnostics\Config\Diagnostics::fromYaml()
     */
    public function testCreationFromYaml()
    {
        $input = Yaml::dump($this->config);

        $this->assertInstanceOf('Evolver\Diagnostics\Config\Diagnostics', DiagnosticsConfig::fromYaml($input));
    }

    /**
     * @covers \Evolver\Diagnostics\Config\Diagnostics::getChecks()
     */
    public function testChecksGetter()
    {
        $config = new DiagnosticsConfig($this->config);

        $this->assertContainsOnlyInstancesOf('Evolver\Diagnostics\Config\Check', $config->getChecks());
    }
}
