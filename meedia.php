#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use RozbehSharahi\Meedia\Command\InitializationCommand;
use RozbehSharahi\Meedia\Command\SyncCommand;
use RozbehSharahi\Meedia\Command\TestConnectionCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new InitializationCommand());
$application->add(new TestConnectionCommand());
$application->add(new SyncCommand());
$application->run();