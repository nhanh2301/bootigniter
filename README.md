# CodeIgniter

[![Build Status](https://travis-ci.org/feryardiant/codeigniter.svg?branch=master)](https://travis-ci.org/feryardiant/codeigniter)

## What is this repo?

This repo is just fork from [EllisLab/CodeIgniter](https://github.com/EllisLab/CodeIgniter) v2.2.0 but come with additional modification based on my own needs.

## What is CodeIgniter?

CodeIgniter is an Application Development Framework - a toolkit - for people who build web sites using PHP. Read more about CodeIgniter [here](http://ellislab.com/codeignite).

## Installation

#### Using Composer

```bash
$ composer create-project feryardiant/codeigniter <foldername>
```

#### Download and extract manually

Create a new folder and `cd` into it

```bash
$ mkdir <foldername> && cd <foldername>
```

Download and extract this package

```bash
$ wget https://github.com/feryardiant/codeigniter/archive/1.0.0.tar.gz -qO - | tar xz
```

That's assuming you have `wget` installed on your system, but if not just simple click this [download link](https://github.com/feryardiant/codeigniter/archive/1.0.0.tar.gz)

#### Install Composer vendors for PHP test unit

**Please note**: I you install it using composer, you can skip this step.

```bash
$ composer install
```

#### Install npm_modules for build process

```bash
$ npm install
```

If you're not familiar with NPM or Node.JS, take a look their [official site](http://nodejs.org/) for more informations.

#### Install bower_componens for front-end dependencies

Make sure you have `bower` installed globally on your system already, but if not simply run this command below and you got it installed in few second, or check [their website](http://bower.io/#install-bower) out for more information

```bash
$ bower install
```

If you're not familiar with NPM or Node.JS, take a look their [official site](http://nodejs.org/) for more informations.

## Changelog

See [here](https://github.com/feryardiant/codeigniter/releases).

## Credits

+ [Ellislab and all contributors of Codeigniter](https://github.com/EllisLab/CodeIgniter)
+ [codeigniter-phpunit](https://github.com/fmalk/codeigniter-phpunit)
+ [Bower Componen](http://bower.io)
  + [jQuery](http://jquery.com) and [jQuery UI](http://jqueryui.com)
  + [jQuery Autosize](http://www.jacklmoore.com/autosize)
  + [jQuery mousewheel](http://brandon.aaron.sh)
  + [jQuery Touch punch](http://touchpunch.furf.com)
  + [Twitter Bootstrap](http://getbootstrap.com)
  + [Bootstrap Datepicker](http://www.eyecon.ro/bootstrap-datepicker)
  + [Bootstrap Switch](http://www.bootstrap-switch.org)
  + [Bootstrap Select](http://silviomoreto.github.com/bootstrap-select/3)
  + [Font Awesome](http://fortawesome.github.io/Font-Awesome/)
  + [Select2](http://ivaynberg.github.io/select2/)

## Copyright and license

**Various trademarks and licenses held by their respective owners.**

## Resources

- [User Guide](http://ellislab.com/codeigniter/user_guide/)
- [Community Forums](http://ellislab.com/forums/)
- [Community Wiki](https://github.com/EllisLab/CodeIgniter/wiki/)
- [Community IRC](http://ellislab.com/codeigniter/irc)
