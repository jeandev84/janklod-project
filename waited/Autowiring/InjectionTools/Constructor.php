<?php

/* Get constructor or method parameters listed by signature */
$className = 'App\\Controllers\\HomeController';
$reflector = new ReflectionClass($className);

// this is how we get some method arguments
# $someMethodArguments = $reflector->getMethod(‘someMethod’)->getParameters();
$someMethodArguments = $reflector->getMethod('index')->getParameters();

// this is how we get constructor parameters
$constructorArguments = $reflector->getConstructor()->getParameters();