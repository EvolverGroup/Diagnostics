# Diagnostics

Configurable diagnostic tests for PHP applications

[![Version](https://img.shields.io/packagist/v/evolver/diagnostics.svg)](https://packagist.org/packages/evolver/diagnostics)
[![Downloads](https://img.shields.io/packagist/dt/evolver/diagnostics.svg)](https://packagist.org/packages/evolver/diagnostics)
[![License](https://img.shields.io/packagist/l/evolver/diagnostics.svg)](https://packagist.org/packages/evolver/diagnostics)
[![Build Status](https://img.shields.io/travis/EvolverGroup/Diagnostics.svg)](https://travis-ci.org/EvolverGroup/Diagnostics)

## Requirements

This application has the following requirements:

- PHP 5.4 or higher

## Installation

Install composer in your project:

```bash
curl -s https://getcomposer.org/installer | php
```

Create a `composer.json` file in your project root:

```json
{
    "require": {
        "evolver/development": "dev-master"
    }
}
```

Install via composer:

```bash
php composer.phar install
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

You may specify any other diagnostics check according to this sample above.

Make diagnostic checks:

```bash
vendor/bin/diagnostics check
```
