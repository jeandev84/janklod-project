<?php

# use Jan\Components\DependencyInjection\Container;

use Jan\Demo\Foo;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../functions.php';


/*
$container = new Container();
$container->set('name', 'Жан-Клод');
echo $container->get('name');
echo '<hr>';
$container->set('Brown');
echo $container->get('Brown');
*/

$app = new \Jan\Foundation\Application(dirname(__DIR__));

/*
$app->bind('name', 'Жан-Клод');
echo $app->get('name');
echo '<br>';

$app->bind('Brown');
echo $app->get('Brown');
*/

echo '<h3>Bind</h3>';
$app->bind(Jan\Demo\Bar::class, new \Jan\Demo\Bar());
$bar = $app->get(Jan\Demo\Bar::class);
dump($bar);


echo '<h3>Singleton</h3>';
$app->singleton(Jan\Demo\Database::class, new \Jan\Demo\Database());
$database1 = $app->get(Jan\Demo\Database::class);
$database2 = $app->get(Jan\Demo\Database::class);
$database3 = $app->get(Jan\Demo\Database::class);
dump($database1);
dump($database2);
dump($database3);


echo '<h3>Make</h3>';
$foo1 = $app->make(Foo::class);
$foo2 = $app->make(Foo::class);
$foo3 = $app->make(Foo::class);
dump($foo1);
dump($foo2);
dump($foo3);


echo '<h3>Bindings params</h3>';
// $app->bindings();

/*
$foo = $app->make(Foo::class);
dump($foo);
*/

/*
$factory = $app->factory(Foo::class);
dump($factory);
*/



/* ================================ */

$app->run();