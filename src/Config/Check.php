<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Evolver\Diagnostics\Config;

/**
 * Check config
 *
 * @package Evolver\Diagnostics
 */
class Check
{
    /**
     * Check config array
     *
     * @var array
     */
    protected $config;

    /**
     * Create check config
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->config = $config;
    }

    /**
     * Get the name of the check
     *
     * @throws \RuntimeException
     * @return string
     */
    public function getName()
    {
        if (!isset($this->config['name']) || !is_string($this->config['name'])) {
            throw new \RuntimeException('Every check needs a name.');
        }

        return $this->config['name'];
    }

    /**
     * Get arguments of the check
     *
     * @return array
     */
    public function getArguments()
    {
        if (!isset($this->config['arguments']) || !is_array($this->config['arguments'])) {
            return array();
        }

        return $this->config['arguments'];
    }

    /**
     * Get the optional alias of the check
     *
     * @return null|string
     */
    public function getAlias()
    {
        if (!isset($this->config['alias']) || !is_string($this->config['alias'])) {
            return null;
        }

        return $this->config['alias'];
    }
}
