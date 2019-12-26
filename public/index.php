<?php

# use Jan\Components\DependencyInjection\Container;

use Jan\Demo\Bar;
use Jan\Demo\Foo;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../functions.php';



$app = new \Jan\Foundation\Application(dirname(__DIR__));

echo '<h3>Binds</h3>';
$app->bind('name', 'Жан-Клод');
echo $app->get('name');
echo '<br>';

echo '<h3>Make</h3>';

/*
$bar = $app->make(Bar::class);
debug($bar);
*/


$foo = $app->make(Foo::class);
debug($foo);




echo '<hr>';
$app->run();