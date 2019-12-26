<?php
namespace Jan\Demo;


/**
 * Class Bar
 * @package Jan\Demo
*/
class Bar
{
    /**
     * Bar constructor.
     * @param string $parameter
     */
    public function __construct(string $parameter = '')
    {
         echo $parameter;
    }
}