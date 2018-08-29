# Toolbox

[![Build Status](https://travis-ci.com/jakzal/toolbox.svg?branch=master)](https://travis-ci.com/jakzal/toolbox)
[![Build Status](https://scrutinizer-ci.com/g/jakzal/toolbox/badges/build.png?b=master)](https://scrutinizer-ci.com/g/jakzal/toolbox/build-status/master)

Helps to discover and install tools.

## Use cases

Toolbox [started its life](https://github.com/jakzal/phpqa/blob/49482ae447d4b6341cf77aac9d51390fe1176e8c/tools.php)
as a simple script in the [phpqa docker image](https://github.com/jakzal/phpqa).
Its purpose was to install set of tools while building the image.
It has been extracted as a separate project to make maintenance easier and open itself for new use cases.

## Installation

Get the `phar` from the [latest release](https://github.com/jakzal/toolbox/releases/latest).
The command below should do the job:

```bash
curl -s https://api.github.com/repos/jakzal/toolbox/releases/latest \
  | grep "browser_download_url.*phar" \
  | cut -d '"' -f 4 \
  | xargs curl -Ls -o toolbox \
  && chmod +x toolbox
```

## Usage

### List available tools

```
toolbox list-tools
```

### Install tools

```
toolbox install
```

### Test if installed tools are usable

```
toolbox test
```
