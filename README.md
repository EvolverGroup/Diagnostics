# Diagnostics

Configurable diagnostic tests for PHP applications based on [ZendDiagnostics](https://github.com/zendframework/ZendDiagnostics)

[![Packagist](https://img.shields.io/packagist/v/evolver/diagnostics.svg)](https://packagist.org/packages/evolver/diagnostics)
[![Downloads](https://img.shields.io/packagist/dt/evolver/diagnostics.svg)](https://packagist.org/packages/evolver/diagnostics)
[![License](https://img.shields.io/packagist/l/evolver/diagnostics.svg)](https://packagist.org/packages/evolver/diagnostics)
[![Build](https://img.shields.io/travis/EvolverGroup/Diagnostics.svg)](https://travis-ci.org/EvolverGroup/Diagnostics)

## Requirements

This application has the following requirements:

- PHP 5.4 or higher

## Installation

You can install this application in two different ways

### As a Phar release (recommended)

Download the latest [release](https://github.com/EvolverGroup/Diagnostics/releases) to yor project root

### As a Composer dependency

Install Composer in your project:

```bash
$ curl -s https://getcomposer.org/installer | php
```

Create a `composer.json` file in your project root:

```json
{
    "require": {
        "evolver/diagnostics": "dev-master"
    }
}
```

Install this package via Composer:

```bash
$ php composer.phar install
```

Or add this package as a requirement to an existing project:

```bash
$ php composer.phar require evolver/diagnostics
```

## Usage

Create a `diagnostics.yml` file in your project root:

```yaml
checks:
  - name: "PhpVersion"
    arguments:
      expectedVersion: "5.4"
      operator: ">="
```

You may specify any other [diagnostic checks](https://github.com/zendframework/ZendDiagnostics#built-in-diagnostics-checks) according to the sample above

This application ships with some additional diagnostic checks:

* [PhpExtension](#phpextension) - make sure given extension is (not) loaded in a defined version

Run diagnostic checks:

1. with the Phar release: `$ php diagnostics.phar check`
2. with the Composer binary: `$ vendor/bin/diagnostics check`

## Additional diagnostic checks

This package comes with some additional diagnostic checks

### PhpExtension

Make sure that a given extension is loaded

```yaml
checks:
  - name: "PhpExtension"
    arguments:
      extensionName: "mbstring"
```

Make sure that a given extension is loaded in a defined version

```yaml
checks:
  - name: "PhpExtension"
    arguments:
      extensionName: "yaml"
      extensionVersion: "1.1.1"
```

Make sure that a given extension is absent

```yaml
checks:
  - name: "PhpExtension"
    arguments:
      extensionName: "apc"
      extensionVersion: false
```
