# BootIgniter

[![Build Status](https://travis-ci.org/feryardiant/bootigniter.svg?branch=master)](https://travis-ci.org/feryardiant/bootigniter)

A [CodeIgniter](https://github.com/EllisLab/CodeIgniter)-based project boilerplate with some additional features that make development easier. It is a Lightweight Open Source package for you to build your own Custom Codeigniter-based application, instead of customize & remove unnecessary features.

## Disclaimer

**THIS PACKAGE IS FOR MY PERSONAL USE ONLY, that's it!**

## Requirements

1. **PHP version 5.3 or newer**

   By default, CodeIgniter can be run on PHP version 5.2.4 ([as minimum requirement](https://github.com/EllisLab/CodeIgniter/#server-requirements)) but in order to use PHPUnit and some order dependencies through composer, I recommend to use 5.3 or newer

2. **Composer**

   Make sure you have Composer installed on your machine. If you don't have it installed, grab it from their [official site](https://getcomposer.org/download/).

3. **Node.JS**

   In this case because I use [Grunt.JS](http://gruntjs.com) and [Bower](http://bower.io), I need Node.JS already installed. If you're not familiar with Node.JS, take a look at their [official site](http://nodejs.org/) for more informations.

## Installation

### Create a directory and `cd` into it.

```bash
$ mkdir <foldername>
$ cd <foldername>
```

### Install using Composer

I assume you're inside of the directory that has been created above.

```bash
$ composer create-project feryardiant/bootigniter .
```

You're on fire now.

### Download manually

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

### Configuration

Last but not least, you need to edit database configuration file that locate in `application/config/database.php` as usual [CI database config](http://ellislab.com/codeigniter/user-guide/database/configuration.html). But before then, you need to create the database at first.

Your code has been ignited right now.

## Uses

### Preview in the browser

If everythings was done, you can run it by type from your terminal.

```bash
$ grunt
```

In order to auto reloading web browser when you finish editing some file, you need to install [LiveReload](http://livereload.com/). If you're using Chrome [this extension](https://chrome.google.com/webstore/detail/livereload/jnihajbhpnppcggbcgedagnkighmdlei) is worth to try or if you're Firefox user please take a look at this [add-ons](https://addons.mozilla.org/en-US/firefox/addon/livereload/).

### Build and Minify JS & CSS files 

```bash
$ grunt build
```

## Changelog

See [here](https://github.com/feryardiant/bootigniter/releases).

## Credits

+ [Ellislab and all contributors of Codeigniter](https://github.com/EllisLab/CodeIgniter)
+ [codeigniter-phpunit](https://github.com/fmalk/codeigniter-phpunit)
+ [Bower Components](http://bower.io), *See my [bower.json](../master/bower.json)*
+ [Node Modules](http://npmjs.org), *See my [package.json](../master/package.json)*

## Copyright and license

**Various trademarks and licenses held by their respective owners.**
