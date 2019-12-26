<?php
namespace Framework;


/**
 * Class Container
 * @package Framework
*/
class Container
{

    /**
     * @param ReflectionClass $dependancyClass
     * @return mixed
    */
    private function resolveClassDependancy(ReflectionClass $dependancyClass)
    {
        $dependancyClassName = $dependancyClass->getName();

        // see service container for exact implementation
        if ($this->serviceContainer->has($dependancyClassName))
        {
            return $this->serviceContainer->get($dependancyClassName);
        }
        // try to match by interfaces
        $interfaces = $dependancyClass->getInterfaces();

        foreach ($interfaces as $interface)
        {
            $resolvedService = $this->resolveClassDependancy($interface);
            if (null !== $resolvedService) {
                return $resolvedService;
            }
        }

        // fallback to parent class
        if ($parentClass = $dependancyClass->getParentClass())
        {
            return $this->resolveClassDependancy($parentClass);
        }
    }
}

/*
Search your service container for the corresponding type or create one, if possible
This is implementation detail, but there is one trick to keep in mind.
Most of the time hint will be an interface or parent class, so it could be useful to try something like:
*/