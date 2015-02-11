<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Evolver\Diagnostics\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * Diagnostics config
 *
 * @package Evolver\Diagnostics\Config
 */
class Diagnostics
{
    /**
     * Diagnostics config array
     *
     * @var array
     */
    protected $config;

    /**
     * Check config list
     *
     * @var Check[]
     */
    private $checks;

    /**
     * Create diagnostics config
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->config = $config;
    }

    /**
     * Create diagnostics config from YAML
     *
     * @param string $input
     *
     * @return Diagnostics
     */
    public static function fromYaml($input)
    {
        return new static(Yaml::parse($input));
    }

    /**
     * Get the check config list
     *
     * @return Check[]
     */
    public function getChecks()
    {
        if (null == $this->checks) {
            if (!isset($this->config['checks']) || !is_array($this->config['checks'])) {
                return array();
            }
            $this->checks = array();
            foreach ($this->config['checks'] as $config) {
                if (is_array($config)) {
                    $this->checks[] = new Check($config);
                }
            }
        }
        return $this->checks;
    }
}
