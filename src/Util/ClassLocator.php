<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@evolver.de>
 */

namespace Evolver\Diagnostics\Util;

/**
 * Class locator util
 *
 * @package Evolver\Diagnostics\Util
 */
class ClassLocator
{
    /**
     * Namespaces
     *
     * @var array
     */
    protected $namespaces;

    /**
     * Create class locator
     *
     * @param array $namespaces
     */
    public function __construct(array $namespaces = array())
    {
        $this->namespaces = $namespaces;
    }

    /**
     * Locate class in the given namespaces and return class name
     *
     * @param string $name
     *
     * @return string
     */
    protected function locateClass($name)
    {
        foreach ($this->namespaces as $namespace) {
            if (class_exists($namespace . '\\' . $name)) {
                $name = $namespace . '\\' . $name;
                break;
            }
        }

        return $name;
    }

    /**
     * Create and return an instance of a class.
     *
     * @param string $name
     * @param array $arguments
     *
     * @throws \RuntimeException
     * @return object
     */
    public function createInstance($name, array $arguments = array())
    {
        if (!class_exists($name)) {
            $name = $this->locateClass($name);
        }

        try {
            $class = new \ReflectionClass($name);
        } catch (\ReflectionException $exception) {
            throw new \RuntimeException(sprintf('Can not find class "%s".', $name), 0, $exception);
        }

        try {
            $instance = $class->newInstanceArgs($arguments);
        } catch (\ReflectionException $exception) {
            throw new \RuntimeException(sprintf('Can not create instance of "%s".', $name), 0, $exception);
        }

        return $instance;
    }
}
