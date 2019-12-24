<?php

require_once __DIR__.'/../vendor/autoload.php';

use Jan\Components\DependencyInjection\Container;

/*
$container = new Container();
$container->set('name', 'Жан-Клод');

echo $container->get('name');

echo '<hr>';

$container->set('Brown');

echo $container->get('Brown');
*/

$app = new \Jan\Foundation\Application(dirname(__DIR__));

$app->bind('name', 'Жан-Клод');

echo $app->get('name');

echo '<hr>';

$app->bind('Brown');

echo $app->get('Brown');



$app->run();