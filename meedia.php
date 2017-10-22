#!/usr/bin/env php
<?php

// Include composer autoload
foreach (array(__DIR__ . '/../../autoload.php', __DIR__ . '/../vendor/autoload.php', __DIR__ . '/vendor/autoload.php') as $file) {
    if (file_exists($file)) {
        require $file;
    }
}

use RozbehSharahi\Meedia\Command\InitializationCommand;
use RozbehSharahi\Meedia\Command\SyncCommand;
use RozbehSharahi\Meedia\Command\TestConnectionCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new InitializationCommand());
$application->add(new TestConnectionCommand());
$application->add(new SyncCommand());
$application->run();