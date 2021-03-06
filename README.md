# Meedia

A tool for creating dummy media files based on live environments.

[![Build Status](https://travis-ci.org/RozbehSharahi/meedia.svg?branch=master)](https://travis-ci.org/RozbehSharahi/meedia)

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
 
- Add default video dummy generator
- Implement fast mode for syncing only files that have changed.

## Installation

`composer require rozbehsharahi/meedia --dev`

## Commands

Initialize your ssh connection to live

`php vendor/rozbehsharahi/meedia/meedia.php meedia:init` 

Test configured connection

`php vendor/rozbehsharahi/meedia/meedia.php meedia:test-connection` 

Install media files either by lock or sync.

`php vendor/rozbehsharahi/meedia/meedia.php meedia:install` 

Update media files by syncing from live.

`php vendor/rozbehsharahi/meedia/meedia.php meedia:update` 

## Fetch image sizes with ImageMagick/GraphicsMagick

On default meedia fetches image sizes through `ImageTreeBuilder` by ImageMagick's `identify` command. In case
 the live servers has GraphicsMagick installed, you can set `useGraphicsMagick` property
 on your configuration to use `gm identify` instead.
 
```json
{
  // ...
  "useGraphicsMagick": true
  // ...
}
```

## Requirements

- PHP 7.1 (cli) on local environment
- SSH2 php extension
- ImageMagick on live server
- Linux developing system (Windows and MAC not tested yet)