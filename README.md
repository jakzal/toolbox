# Toolbox

[![Build Status](https://github.com/jakzal/toolbox/workflows/Build/badge.svg)](https://github.com/jakzal/toolbox/actions)
[![Build Status](https://scrutinizer-ci.com/g/jakzal/toolbox/badges/build.png?b=master)](https://scrutinizer-ci.com/g/jakzal/toolbox/build-status/master)

Helps to discover and install tools.

## Use cases

Toolbox [started its life](https://github.com/jakzal/phpqa/blob/49482ae447d4b6341cf77aac9d51390fe1176e8c/tools.php)
as a simple script in the [phpqa docker image](https://github.com/jakzal/phpqa).
Its purpose was to install set of tools while building the docker image and it's still its main goal.
It has been extracted as a separate project to make maintenance easier and enable new use cases.

## Available tools

| Name | Description | PHP 8.0 | PHP 8.1 | PHP 8.2 |
| :--- | :---------- | :------ | :------ | :------ |
| behat | [Helps to test business expectations](http://behat.org/) | &#x2705; | &#x2705; | &#x2705; |
| box | [Fast, zero config application bundler with PHARs](https://github.com/humbug/box) | &#x274C; | &#x2705; | &#x2705; |
| box-3 | [Fast, zero config application bundler with PHARs](https://github.com/humbug/box) | &#x2705; | &#x2705; | &#x274C; |
| churn | [Discovers good candidates for refactoring](https://github.com/bmitch/churn-php) | &#x2705; | &#x2705; | &#x2705; |
| codeception | [Codeception is a BDD-styled PHP testing framework](https://codeception.com/) | &#x2705; | &#x2705; | &#x2705; |
| composer | [Dependency Manager for PHP](https://getcomposer.org/) | &#x2705; | &#x2705; | &#x2705; |
| composer-bin-plugin | [Composer plugin to install bin vendors in isolated locations](https://github.com/bamarni/composer-bin-plugin) | &#x2705; | &#x2705; | &#x2705; |
| composer-normalize | [Composer plugin to normalize composer.json files](https://github.com/ergebnis/composer-normalize) | &#x2705; | &#x2705; | &#x2705; |
| composer-require-checker | [Verify that no unknown symbols are used in the sources of a package.](https://github.com/maglnet/ComposerRequireChecker) | &#x274C; | &#x2705; | &#x2705; |
| composer-require-checker-3 | [Verify that no unknown symbols are used in the sources of a package.](https://github.com/maglnet/ComposerRequireChecker) | &#x2705; | &#x2705; | &#x2705; |
| composer-unused | [Show unused packages by scanning your code](https://github.com/icanhazstring/composer-unused) | &#x2705; | &#x2705; | &#x2705; |
| dephpend | [Detect flaws in your architecture](https://dephpend.com/) | &#x2705; | &#x2705; | &#x2705; |
| deprecation-detector | [Finds usages of deprecated code](https://github.com/sensiolabs-de/deprecation-detector) | &#x2705; | &#x2705; | &#x2705; |
| deptrac | [Enforces dependency rules between software layers](https://github.com/qossmic/deptrac) | &#x274C; | &#x2705; | &#x2705; |
| diffFilter | [Applies QA tools to run on a single pull request](https://github.com/exussum12/coverageChecker) | &#x2705; | &#x2705; | &#x2705; |
| ecs | [Sets up and runs coding standard checks](https://github.com/Symplify/EasyCodingStandard) | &#x2705; | &#x2705; | &#x2705; |
| infection | [AST based PHP Mutation Testing Framework](https://infection.github.io/) | &#x274C; | &#x2705; | &#x2705; |
| larastan | [PHPStan extension for Laravel](https://github.com/nunomaduro/larastan) | &#x2705; | &#x2705; | &#x2705; |
| local-php-security-checker | [Checks composer dependencies for known security vulnerabilities](https://github.com/fabpot/local-php-security-checker) | &#x2705; | &#x2705; | &#x2705; |
| parallel-lint | [Checks PHP file syntax](https://github.com/php-parallel-lint/PHP-Parallel-Lint) | &#x2705; | &#x2705; | &#x2705; |
| paratest | [Parallel testing for PHPUnit](https://github.com/paratestphp/paratest) | &#x2705; | &#x2705; | &#x2705; |
| pdepend | [Static Analysis Tool](https://pdepend.org/) | &#x2705; | &#x2705; | &#x2705; |
| phan | [Static Analysis Tool](https://github.com/phan/phan) | &#x2705; | &#x2705; | &#x2705; |
| phive | [PHAR Installation and Verification Environment](https://phar.io/) | &#x2705; | &#x2705; | &#x2705; |
| php-coupling-detector | [Detects code coupling issues](https://akeneo.github.io/php-coupling-detector/) | &#x2705; | &#x2705; | &#x2705; |
| php-cs-fixer | [PHP Coding Standards Fixer](http://cs.symfony.com/) | &#x2705; | &#x2705; | &#x2705; |
| php-fuzzer | [A fuzzer for PHP, which can be used to find bugs in libraries by feeding them 'random' inputs](https://github.com/nikic/PHP-Fuzzer) | &#x2705; | &#x2705; | &#x2705; |
| php-semver-checker | [Suggests a next version according to semantic versioning](https://github.com/tomzx/php-semver-checker) | &#x2705; | &#x2705; | &#x2705; |
| phpa | [Checks for weak assumptions](https://github.com/rskuipers/php-assumptions) | &#x2705; | &#x2705; | &#x2705; |
| phparkitect | [Helps to put architectural constraints in a PHP code base](https://github.com/phparkitect/arkitect) | &#x2705; | &#x2705; | &#x2705; |
| phpat | [Easy to use architecture testing tool](https://github.com/carlosas/phpat) | &#x2705; | &#x2705; | &#x2705; |
| phpbench | [PHP Benchmarking framework](https://github.com/phpbench/phpbench) | &#x2705; | &#x2705; | &#x2705; |
| phpca | [Finds usage of non-built-in extensions](https://github.com/wapmorgan/PhpCodeAnalyzer) | &#x2705; | &#x2705; | &#x2705; |
| phpcb | [PHP Code Browser](https://github.com/mayflower/PHP_CodeBrowser) | &#x2705; | &#x2705; | &#x2705; |
| phpcbf | [Automatically corrects coding standard violations](https://github.com/squizlabs/PHP_CodeSniffer) | &#x2705; | &#x2705; | &#x2705; |
| phpcodesniffer-composer-install | [Easy installation of PHP_CodeSniffer coding standards (rulesets).](https://github.com/Dealerdirect/phpcodesniffer-composer-installer) | &#x2705; | &#x2705; | &#x2705; |
| phpcov | [a command-line frontend for the PHP_CodeCoverage library](https://github.com/sebastianbergmann/phpcov) | &#x274C; | &#x2705; | &#x2705; |
| phpcpd | [Copy/Paste Detector](https://github.com/sebastianbergmann/phpcpd) | &#x2705; | &#x2705; | &#x2705; |
| phpcs | [Detects coding standard violations](https://github.com/squizlabs/PHP_CodeSniffer) | &#x2705; | &#x2705; | &#x2705; |
| phpcs-security-audit | [Finds vulnerabilities and weaknesses related to security in PHP code](https://github.com/FloeDesignTechnologies/phpcs-security-audit) | &#x2705; | &#x2705; | &#x2705; |
| phpda | [Generates dependency graphs](https://mamuz.github.io/PhpDependencyAnalysis/) | &#x2705; | &#x274C; | &#x274C; |
| phpdd | [Finds usage of deprecated features](http://wapmorgan.github.io/PhpDeprecationDetector) | &#x2705; | &#x2705; | &#x2705; |
| phpDocumentor | [Documentation generator](https://www.phpdoc.org/) | &#x2705; | &#x2705; | &#x2705; |
| phpinsights | [Analyses code quality, style, architecture and complexity](https://phpinsights.com/) | &#x2705; | &#x2705; | &#x2705; |
| phplint | [Lints php files in parallel](https://github.com/overtrue/phplint) | &#x2705; | &#x2705; | &#x2705; |
| phploc | [A tool for quickly measuring the size of a PHP project](https://github.com/sebastianbergmann/phploc) | &#x2705; | &#x2705; | &#x2705; |
| phpmd | [A tool for finding problems in PHP code](https://phpmd.org/) | &#x2705; | &#x2705; | &#x2705; |
| phpmetrics | [Static Analysis Tool](http://www.phpmetrics.org/) | &#x2705; | &#x2705; | &#x2705; |
| phpmnd | [Helps to detect magic numbers](https://github.com/povils/phpmnd) | &#x2705; | &#x2705; | &#x2705; |
| phpspec | [SpecBDD Framework](http://www.phpspec.net/) | &#x2705; | &#x2705; | &#x274C; |
| phpstan | [Static Analysis Tool](https://github.com/phpstan/phpstan) | &#x2705; | &#x2705; | &#x2705; |
| phpstan-banned-code | [PHPStan rules for detecting calls to specific functions you don't want in your project](https://github.com/ekino/phpstan-banned-code) | &#x2705; | &#x2705; | &#x2705; |
| phpstan-beberlei-assert | [PHPStan extension for beberlei/assert](https://github.com/phpstan/phpstan-beberlei-assert) | &#x2705; | &#x2705; | &#x2705; |
| phpstan-deprecation-rules | [PHPStan rules for detecting deprecated code](https://github.com/phpstan/phpstan-deprecation-rules) | &#x2705; | &#x2705; | &#x2705; |
| phpstan-doctrine | [Doctrine extensions for PHPStan](https://github.com/phpstan/phpstan-doctrine) | &#x2705; | &#x2705; | &#x2705; |
| phpstan-ergebnis-rules | [Additional rules for PHPstan](https://github.com/ergebnis/phpstan-rules) | &#x2705; | &#x2705; | &#x2705; |
| phpstan-exception-rules | [PHPStan rules for checked and unchecked exceptions](https://github.com/pepakriz/phpstan-exception-rules) | &#x2705; | &#x2705; | &#x2705; |
| phpstan-larastan | [Separate installation of phpstan for larastan](https://github.com/phpstan/phpstan) | &#x2705; | &#x2705; | &#x2705; |
| phpstan-phpunit | [PHPUnit extensions and rules for PHPStan](https://github.com/phpstan/phpstan-phpunit) | &#x2705; | &#x2705; | &#x2705; |
| phpstan-strict-rules | [Extra strict and opinionated rules for PHPStan](https://github.com/phpstan/phpstan-strict-rules) | &#x2705; | &#x2705; | &#x2705; |
| phpstan-symfony | [Symfony extension for PHPStan](https://github.com/phpstan/phpstan-symfony) | &#x2705; | &#x2705; | &#x2705; |
| phpstan-webmozart-assert | [PHPStan extension for webmozart/assert](https://github.com/phpstan/phpstan-webmozart-assert) | &#x2705; | &#x2705; | &#x2705; |
| phpunit | [The PHP testing framework](https://phpunit.de/) | &#x274C; | &#x2705; | &#x2705; |
| phpunit-8 | [The PHP testing framework (8.x version)](https://phpunit.de/) | &#x2705; | &#x2705; | &#x2705; |
| phpunit-9 | [The PHP testing framework (9.x version)](https://phpunit.de/) | &#x2705; | &#x2705; | &#x2705; |
| pint | [Opinionated PHP code style fixer for Laravel](https://github.com/laravel/pint) | &#x2705; | &#x2705; | &#x2705; |
| psalm | [Finds errors in PHP applications](https://psalm.dev/) | &#x2705; | &#x2705; | &#x2705; |
| psalm-plugin-doctrine | [Stubs to let Psalm understand Doctrine better](https://github.com/weirdan/doctrine-psalm-plugin) | &#x2705; | &#x2705; | &#x2705; |
| psalm-plugin-phpunit | [Psalm plugin for PHPUnit](https://github.com/psalm/psalm-plugin-phpunit) | &#x2705; | &#x2705; | &#x2705; |
| psalm-plugin-symfony | [Psalm Plugin for Symfony](https://github.com/psalm/psalm-plugin-symfony) | &#x2705; | &#x2705; | &#x2705; |
| psecio-parse | [Scans code for potential security-related issues](https://github.com/psecio/parse) | &#x2705; | &#x2705; | &#x2705; |
| rector | [Tool for instant code upgrades and refactoring](https://github.com/rectorphp/rector) | &#x2705; | &#x2705; | &#x2705; |
| roave-backward-compatibility-check | [Tool to compare two revisions of a class API to check for BC breaks](https://github.com/Roave/BackwardCompatibilityCheck) | &#x2705; | &#x2705; | &#x2705; |
| simple-phpunit | [Provides utilities to report legacy tests and usage of deprecated code](https://symfony.com/doc/current/components/phpunit_bridge.html) | &#x2705; | &#x2705; | &#x2705; |
| twig-lint | [Standalone cli twig 1.X linter](https://github.com/asm89/twig-lint) | &#x2705; | &#x2705; | &#x2705; |
| twig-linter | [Standalone cli twig 3.X linter](https://github.com/sserbin/twig-linter) | &#x2705; | &#x2705; | &#x2705; |
| twigcs | [The missing checkstyle for twig!](https://github.com/friendsoftwig/twigcs) | &#x2705; | &#x2705; | &#x2705; |
| yaml-lint | [Compact command line utility for checking YAML file syntax](https://github.com/j13k/yaml-lint) | &#x2705; | &#x2705; | &#x2705; |

### Removed tools

| Name | Summary |  
| :--- | :------ |
| analyze | [Visualizes metrics and source code](https://github.com/Qafoo/QualityAnalyzer) |
| box-legacy | [Legacy version of box](https://box-project.github.io/box2/) |
| composer-normalize | [Composer plugin to normalize composer.json files](https://github.com/localheinz/composer-normalize) |
| design-pattern | [Detects design patterns](https://github.com/Halleck45/DesignPatternDetector) |
| parallel-lint | [Checks PHP file syntax](https://github.com/JakubOnderka/PHP-Parallel-Lint) |
| php-formatter | [Custom coding standards fixer](https://github.com/mmoreram/php-formatter) |
| phpcf | [Finds usage of deprecated features](http://wapmorgan.github.io/PhpCodeFixer/) |
| phpdoc-to-typehint | [Automatically adds type hints and return types based on PHPDocs](https://github.com/dunglas/phpdoc-to-typehint) |
| phpstan-localheinz-rules | [Additional rules for PHPstan](https://github.com/localheinz/phpstan-rules) |
| phpunit-5 | [The PHP testing framework (5.x version)](https://phpunit.de/) |
| phpunit-7 | [The PHP testing framework (7.x version)](https://phpunit.de/) |
| security-checker | [Checks composer dependencies for known security vulnerabilities](https://github.com/sensiolabs/security-checker) |
| testability | [Analyses and reports testability issues of a php codebase](https://github.com/edsonmedina/php_testability) |

## Installation

Get the `toolbox.phar` from the [latest release](https://github.com/jakzal/toolbox/releases/latest).
The command below should do the job:

```bash
curl -Ls https://github.com/jakzal/toolbox/releases/latest/download/toolbox.phar -o toolbox && chmod +x toolbox
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
./toolbox list-tools --exclude-tag exclude-php:8.2 --exclude-tag foo --tag bar
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
./toolbox install --exclude-tag exclude-php:8.2 --exclude-tag foo --tag bar
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
./toolbox test --exclude-tag exclude-php:8.2 --exclude-tag foo --tag bar
```

### Tools definitions

By default the following files are used to load tool definitions:

* `resources/pre-installation.json`
* `resources/architecture.json`
* `resources/checkstyle.json`
* `resources/compatibility.json`
* `resources/composer.json`
* `resources/deprecation.json`
* `resources/documentation.json`
* `resources/linting.json`
* `resources/metrics.json`
* `resources/phpstan.json`
* `resources/psalm.json`
* `resources/refactoring.json`
* `resources/security.json`
* `resources/test.json`
* `resources/tools.json`

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
* `exclude-php:8.1`, `exclude-php:8.2` etc - used to exclude installation on the specified php version.

## Contributing

Please read the [Contributing guide](CONTRIBUTING.md) to learn about contributing to this project.
Please note that this project is released with a [Contributor Code of Conduct](CODE_OF_CONDUCT.md).
By participating in this project you agree to abide by its terms.
