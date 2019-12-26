<?php

# use Jan\Components\DependencyInjection\Container;

use Jan\Demo\Foo;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../functions.php';



$app = new \Jan\Foundation\Application(dirname(__DIR__));


$app->run();