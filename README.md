# Meedia

A tool for creating dummy media files based on live environments.

<img src="https://travis-ci.org/RozbehSharahi/meedia.svg?branch=master" />

## Introduction

Meedia will provide dummy media files for dev-environments by connecting
 to a live setup via SSH and creating a configuration tree to generate example
 dummy images in the right proportions. 
 
Additional dummy generators can be added by configuration.

The tool contains an `meedia:install` and `meedia:update` command, which
 will work fine for integrating it into review environments and continues integration
 systems.

This project is still in progress and experimental. In the future following features
 shall be added:
 
- Abstraction of tree builder using interface to be able to create project specific
 lookup scripts.
- Add default video dummy generator
- Implement fast mode for syncing only files that have changed.

## Installation

`composer require rozbehsharahi/meedia --dev`

## Commands

Initialize your ssh connection to live

`php vendor/rozbehsharahi/meedia/meedia.php meedia:init` 

---

Test configured connection

`php vendor/rozbehsharahi/meedia/meedia.php meedia:test-connection` 

---

Install media files either by lock or sync.

`php vendor/rozbehsharahi/meedia/meedia.php meedia:install` 

---

Update media files by syncing from live.

`php vendor/rozbehsharahi/meedia/meedia.php meedia:update` 

## Requirements

- PHP 7.1 (cli) on local environment
- SSH2 php extension
- ImageMagick on live server
- Linux developing system (Windows and MAC not tested yet)