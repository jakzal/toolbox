# Toolbox

[![Build Status](https://travis-ci.com/jakzal/toolbox.svg?branch=master)](https://travis-ci.com/jakzal/toolbox)
[![Build Status](https://scrutinizer-ci.com/g/jakzal/toolbox/badges/build.png?b=master)](https://scrutinizer-ci.com/g/jakzal/toolbox/build-status/master)

Helps to discover and install tools.

## Use cases

Toolbox [started its life](https://github.com/jakzal/phpqa/blob/49482ae447d4b6341cf77aac9d51390fe1176e8c/tools.php)
as a simple script in the [phpqa docker image](https://github.com/jakzal/phpqa).
Its purpose was to install set of tools while building the docker image and it's still its main goal.
It has been extracted as a separate project to make maintenance easier and enable new use cases.

## Available tools

* composer - [Dependency Manager for PHP](https://getcomposer.org/)
* composer-bin-plugin - [Composer plugin to install bin vendors in isolated locations](https://github.com/bamarni/composer-bin-plugin)
* box - [An application for building and managing Phars](https://box-project.github.io/box2/)
* analyze - [Visualizes metrics and source code](https://github.com/Qafoo/QualityAnalyzer)
* behat - [Helps to test business expectations](http://behat.org/)
* churn - [Discovers good candidates for refactoring](https://github.com/bmitch/churn-php)
* dephpend - [Detect flaws in your architecture](https://dephpend.com/)
* deprecation-detector - [Finds usages of deprecated code](https://github.com/sensiolabs-de/deprecation-detector)
* deptrac - [Enforces dependency rules between software layers](https://github.com/sensiolabs-de/deptrac)
* design-pattern - [Detects design patterns](https://github.com/Halleck45/DesignPatternDetector)
* diffFilter - [Applies QA tools to run on a single pull request](https://github.com/exussum12/coverageChecker)
* ecs - [Sets up and runs coding standard checks](https://github.com/Symplify/EasyCodingStandard)
* infection - [AST based PHP Mutation Testing Framework](https://infection.github.io/)
* parallel-lint - [Checks PHP file syntax](https://github.com/JakubOnderka/PHP-Parallel-Lint)
* pdepend - [Static Analysis Tool](https://pdepend.org/)
* phan - [Static Analysis Tool](https://github.com/phan/phan)
* php-coupling-detector - [Detects code coupling issues](https://akeneo.github.io/php-coupling-detector/)
* php-cs-fixer - [PHP Coding Standards Fixer](http://cs.sensiolabs.org/)
* php-formatter - [Custom coding standards fixer](https://github.com/mmoreram/php-formatter)
* php-semver-checker - [Suggests a next version according to semantic versioning](https://github.com/tomzx/php-semver-checker)
* phpDocumentor - [Documentation generator](https://www.phpdoc.org/)
* phpbench - [PHP Benchmarking framework](https://github.com/phpbench/phpbench)
* phpa - [Checks for weak assumptions](https://github.com/rskuipers/php-assumptions)
* phpca - [Finds usage of non-built-in extensions](https://github.com/wapmorgan/PhpCodeAnalyzer)
* phpcb - [PHP Code Browser](https://github.com/mayflower/PHP_CodeBrowser)
* phpcbf - [Automatically corrects coding standard violations](https://github.com/squizlabs/PHP_CodeSniffer)
* phpcf - [Finds usage of deprecated features](http://wapmorgan.github.io/PhpCodeFixer/)
* phpcov - [a command-line frontend for the PHP_CodeCoverage library](https://github.com/sebastianbergmann/phpcov)
* phpcpd - [Copy/Paste Detector](https://github.com/sebastianbergmann/phpcpd)
* phpcs - [Detects coding standard violations](https://github.com/squizlabs/PHP_CodeSniffer)
* phpda - [Generates dependency graphs](https://mamuz.github.io/PhpDependencyAnalysis/)
* phpdoc-to-typehint - [Automatically adds type hints and return types based on PHPDocs](https://github.com/dunglas/phpdoc-to-typehint)
* phplint - [Lints php files in parallel](https://github.com/overtrue/phplint)
* phploc - [A tool for quickly measuring the size of a PHP project](https://github.com/sebastianbergmann/phploc)
* phpmd - [A tool for finding problems in PHP code](https://phpmd.org/)
* phpmetrics - [Static Analysis Tool](http://www.phpmetrics.org/)
* phpmnd - [Helps to detect magic numbers](https://github.com/povils/phpmnd)
* phpspec - [SpecBDD Framework](http://www.phpspec.net/)
* phpstan - [Static Analysis Tool](https://github.com/phpstan/phpstan)
* phpstan-deprecation-rules - [PHPStan rules for detecting deprecated code](https://github.com/phpstan/phpstan-deprecation-rules)
* phpstan-strict-rules - [Extra strict and opinionated rules for PHPStan](https://github.com/phpstan/phpstan-strict-rules)
* phpstan-doctrine - [Doctrine extensions for PHPStan](https://github.com/phpstan/phpstan-doctrine)
* phpstan-phpunit - [PHPUnit extensions and rules for PHPStan](https://github.com/phpstan/phpstan-phpunit)
* phpstan-symfony - [Symfony extension for PHPStan](https://github.com/phpstan/phpstan-symfony)
* phpstan-beberlei-assert - [PHPStan extension for beberlei/assert](https://github.com/phpstan/phpstan-beberlei-assert)
* phpstan-webmozart-assert - [PHPStan extension for webmozart/assert](https://github.com/phpstan/phpstan-webmozart-assert)
* phpstan-exception-rules - [PHPStan rules for checked and unchecked exceptions](https://github.com/pepakriz/phpstan-exception-rules)
* phpunit - [The PHP testing framework](https://phpunit.de/)
* phpunit-7 - [The PHP testing framework (7.x version)](https://phpunit.de/)
* psalm - [Finds errors in PHP applications](https://getpsalm.org/)
* psecio-parse - [Scans code for potential security-related issues](https://github.com/psecio/parse)
* rector - [Tool for instant code upgrades and refactoring](https://github.com/rectorphp/rector)
* roave-backward-compatibility-check - [Tool to compare two revisions of a class API to check for BC breaks](https://github.com/Roave/BackwardCompatibilityCheck)
* security-checker - [Checks composer dependencies for known security vulnerabilities](https://github.com/sensiolabs/security-checker)
* simple-phpunit - [Provides utilities to report legacy tests and usage of deprecated code](https://symfony.com/doc/current/components/phpunit_bridge.html)
* testability - [Analyses and reports testability issues of a php codebase](https://github.com/edsonmedina/php_testability)

## Installation

Get the `toolbox.phar` from the [latest release](https://github.com/jakzal/toolbox/releases/latest).
The command below should do the job:

```bash
curl -s https://api.github.com/repos/jakzal/toolbox/releases/latest \
  | grep "browser_download_url.*toolbox.phar" \
  | cut -d '"' -f 4 \
  | xargs curl -Ls -o toolbox \
  && chmod +x toolbox
```

## Usage

### List available tools

```
./toolbox list-tools
```

#### Filter tools by tags

To exclude some tools from the listing multiple `--exclude-tag` options can be added.
The `--tag` option can be used to filter tools by tags.

```
./toolbox list-tools --exclude-tag exclude-php:7.3 --exclude-tag foo --tag bar
```

### Install tools

```
./toolbox install
```

#### Install tools in a custom directory

By default tools are installed in the `/usr/local/bin` directory. To perform an installation in another location,
pass the `--target-dir` option to the `install` command. Also, to change the location composer packages are installed in,
export the `COMPOSER_HOME` environment variable.

```
mkdir /tools
export COMPOSER_HOME=/tools/.composer
export PATH="/tools:$COMPOSER_HOME/vendor/bin:$PATH"
./toolbox install --target-dir /tools
```

The target dir can also be configured with the `TOOLBOX_TARGET_DIR` environment variable.

#### Dry run

To only see what commands would be executed, use the dry run mode:

```
./toolbox install --dry-run
```

#### Filter tools by tags

To exclude some tools from the installation multiple `--exclude-tag` options can be added.
The `--tag` option can be used to filter tools by tags.

```
./toolbox install --exclude-tag exclude-php:7.3 --exclude-tag foo --tag bar
```

### Test if installed tools are usable

```
./toolbox test
```

#### Dry run

To only see what commands would be executed, use the dry run mode:

```
./toolbox test --dry-run
```

#### Filter tools by tags

To exclude some tools from the generated test command multiple `--exclude-tag` options can be added.
The `--tag` option can be used to filter tools by tags.

```
./toolbox test --exclude-tag exclude-php:7.3 --exclude-tag foo --tag bar
```

### Tools definitions

By default `resources/pre-installation.json` and `resources/tools.json` are used to load tool definitions.
Definitions can be loaded from customised files by passing the `--tools` option(s):

```
./toolbox list-tools --tools path/to/file1.json --tools path/to/file2.json
```

Tool definition location(s) can be also specified with the `TOOLBOX_JSON` environment variable:

```
TOOLBOX_JSON='path/to/file1.json,path/to/file2.json' ./toolbox list-tools
```

### Tool tags

Tools can be tagged in order to enable grouping and filtering them.

The tags below have a special meaning:

* `pre-installation` - these tools will be installed before any other tools.
* `exclude-php:7.3`, `exclude-php:7.1` etc - used to exclude installation on the specified php version.

## Contributing

Please read the [Contributing guide](CONTRIBUTING.md) to learn about contributing to this project.
Please note that this project is released with a [Contributor Code of Conduct](CODE_OF_CONDUCT.md).
By participating in this project you agree to abide by its terms.
