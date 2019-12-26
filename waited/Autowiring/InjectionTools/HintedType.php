<?php

/* Get the hinted type for each of the parameters */

foreach ($constructorArguments as $argumentIndex => $constructorArgument) {

    $argumentClassHint = $constructorArgument->getClass();
    //…
}

/*
The return value of getClass is null if the argument is not hinted or not class.
This can be used to determine if this is resolvable.
Another check we can do is look at the argument type using $constructorArgument->getType()
*/