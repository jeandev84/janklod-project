<?php


/**
 * @param $arr
 * @param bool $die
*/
function debug($arr, $die = false)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
    if($die) die;
}


function dump($arr)
{
    echo '<pre>';
    var_dump($arr);
    echo '</pre>';
}


function dd($arr)
{
    dump($arr);
    die;
}