<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@gravitymedia.de>
 */

namespace Evolver\Diagnostics\Check;

use Herrera\Version\Comparator;
use Herrera\Version\Parser;
use Herrera\Version\Validator;
use Herrera\Version\Version;
use ZendDiagnostics\Check\AbstractCheck;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;

/**
 * PHP extension check
 *
 * @package Evolver\Diagnostics\Check
 */
class PhpExtension extends AbstractCheck implements CheckInterface
{
    /**
     * Extension name
     *
     * @var string
     */
    protected $name;

    /**
     * Extension version
     *
     * @var bool|string
     */
    protected $version;

    /**
     * Version comparison operator
     *
     * @var string
     */
    protected $operator;

    /**
     * Create PHP extension check
     *
     * @param string $name
     * @param bool|string $version
     * @param string $operator
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($name, $version = true, $operator = '>=')
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('Expected a extension name as string');
        }

        if (!is_bool($version) && !is_string($version)) {
            throw new \InvalidArgumentException('Expected a extension version as string or boolean');
        }

        $possibleOperators = array('<', 'lt', '<=', 'le', '>', 'gt', '>=', 'ge', '==', '=', 'eq', '!=', '<>', 'ne');
        if (!in_array($operator, $possibleOperators)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected a supported operator. Possible operators are: %s',
                implode(', ', $possibleOperators)
            ));
        }

        $this->name = $name;
        $this->version = $version;
        $this->operator = $operator;
    }

    /**
     * Parse version string
     *
     * @param string $version
     *
     * @return null|\Herrera\Version\Version
     */
    public function parseVersion($version)
    {
        if (Validator::isVersion($version)) {
            return Parser::toVersion($version);
        }
        return null;
    }

    /**
     * Compare versions
     *
     * @param Version $actualVersion
     * @param Version $expectedVersion
     *
     * @return bool
     */
    public function compareVersions(Version $actualVersion, Version $expectedVersion)
    {
        $operators = array(
            '<' => Comparator::LESS_THAN, 'lt' => Comparator::LESS_THAN,
            '>' => Comparator::GREATER_THAN, 'gt' => Comparator::GREATER_THAN,
            '==' => Comparator::EQUAL_TO, '=' => Comparator::EQUAL_TO, 'eq' => Comparator::EQUAL_TO);

        if (isset($operators[$this->operator])) {
            return $operators[$this->operator] === Comparator::compareTo($actualVersion, $expectedVersion);
        }

        $operators = array(
            '<=' => Comparator::GREATER_THAN, 'le' => Comparator::GREATER_THAN,
            '>=' => Comparator::LESS_THAN, 'ge' => Comparator::LESS_THAN,
            '!=' => Comparator::EQUAL_TO, '<>' => Comparator::EQUAL_TO, 'ne' => Comparator::EQUAL_TO);

        return $operators[$this->operator] !== Comparator::compareTo($actualVersion, $expectedVersion);
    }

    /**
     * @inheritdoc
     */
    public function check()
    {
        if (false === $this->version) {
            if (extension_loaded($this->name)) {
                return new Failure(sprintf('Extension %s must be absent.', $this->name));
            }
            return new Success(sprintf('Extension %s is is absent.', $this->name));
        }

        if (!extension_loaded($this->name)) {
            return new Failure(sprintf('Extension %s is not available.', $this->name));
        }

        $expectedVersion = $this->parseVersion($this->version);
        $actualVersion = $this->parseVersion(phpversion($this->name));

        if (!$expectedVersion instanceof Version || !$actualVersion instanceof Version) {
            return new Success(sprintf('Extension %s is loaded.', $this->name));
        }

        if ($this->compareVersions($actualVersion, $expectedVersion)) {
            return new Success(sprintf('Extension %s %s is loaded.', $this->name, $actualVersion));
        }

        return new Failure(sprintf(
            'Extension %s must be loaded in version %s%s.',
            $this->name,
            $this->operator,
            $this->version
        ));
    }
}
