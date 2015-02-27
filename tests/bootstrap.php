<?php
/**
 * This file is part of the evolver diagnostics project.
 *
 * @copyright Copyright (c) 2015, evolver media GmbH & Co. KG <info@evolver.de>
 * @author Daniel Schröder <daniel.schroeder@evolver.de>
 */

/**
 * Report all errors
 */
error_reporting(-1);

/**
 * Set up memory limit for this application
 */
ini_set('memory_limit', '128M');

/**
 * Set up default timezone for this application
 */
date_default_timezone_set('Europe/Berlin');

/**
 * Files will be created as -rw-rw-r--
 * Directories will be creates as drwxrwxr-x
 */
umask(0002);

/**
 * Make everything relative to the application root
 */
chdir(dirname(__DIR__));

/**
 * Initialize autoloader
 */
require 'vendor/autoload.php';
