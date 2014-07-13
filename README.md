# BootIgniter

[![Build Status](https://travis-ci.org/feryardiant/bootigniter.svg?branch=master)](https://travis-ci.org/feryardiant/bootigniter)

An [CodeIgniter](https://github.com/EllisLab/CodeIgniter)-based project boilerplate with some additional features that gives scalable development framework for all, Developers & Designers. It is a Lightweight Open codes package for you to build your own Custom Codeigniter-based application, instead of customize & removing unnecessary features.

## Disclaimer

**THIS PACKAGE IS FOR MY PERSONAL USE ONLY, that's it!**

## Requirements

1. **PHP version 5.3 or newer**

   By default CodeIgniter can be run on PHP version 5.2.4 ([as minimum requirement](https://github.com/EllisLab/CodeIgniter/#server-requirements)) but in order to use PHPUnit and some order dependencies through composer, I recommend to use 5.3 or newer

2. **Composer**

   Make sure you have Composer installed on your machine. If you don't have it installed, grab it from their [official site](https://getcomposer.org/download/).

3. **Node.JS**

   In this case because I use [Grunt.JS](http://gruntjs.com) and [Bower](http://bower.io) I need Node.JS already installed. If you're not familiar with Node.JS, take a look at their [official site](http://nodejs.org/) for more informations.

## Installation

#### Create a directory and `cd` into it.

```bash
$ mkdir <foldername>
$ cd <foldername>
```

#### Install using Composer

I assuming you're inside of directory that has been created above.

```bash
$ composer create-project feryardiant/bootigniter .
```

You're on fire now.

#### Download manually

1. Download and extract this package

   ```bash
   $ wget https://github.com/feryardiant/bootigniter/archive/master.tar.gz -qO - | tar xz | shopt -s dotglob && cp -rf bootigniter-master/* . && rm -rf bootigniter-master/
   ```

   That's assuming you have `wget` installed on your system, but if not just simply click this [download link](https://github.com/feryardiant/bootigniter/archive/1.0.1.tar.gz)

2. Install Composer Dependencies

   ```bash
   $ composer install
   ```

3. Install NPM Dependencies

   ```bash
   $ npm install
   ```

Your code has been ignited right now.

## Uses

### Preview in the browser

```bash
$ grunt php
```

### PHP Lint and Test Unit

```bash
$ grunt phptest
```

### Watch all changes

```bash
$ grunt watch
```

### JS & CSS

#### SOON :grin:

## Changelog

See [here](https://github.com/feryardiant/bootigniter/releases).

## Credits

+ [Ellislab and all contributors of Codeigniter](https://github.com/EllisLab/CodeIgniter)
+ [codeigniter-phpunit](https://github.com/fmalk/codeigniter-phpunit)
+ [Bower Components](http://bower.io), *See my [bower.json](../master/bower.json)*
+ [Node Modules](http://npmjs.org), *See my [package.json](../master/package.json)*

## Copyright and license

**Various trademarks and licenses held by their respective owners.**
