<?php
namespace Framework\FileSystem;



/**
 * Class File
 * @package Framework\FileSystem
 */
class File
{

    /** @var string */
    private $root;


    /**
     * File constructor.
     * @param string $root
    */
    public function __construct(string $root)
    {
        $this->root = $root;
    }


    /**
     * @param string $path
     * @return bool
    */
    public function generate(string $path)
    {
        $direction = $this->to(pathinfo($path)['dirname']);
        if(! is_dir($direction))
        {
            mkdir($direction, 0777, true);
        }
        return touch($path) ? $path : false;
    }


    /**
     * @param string $path
     * @return string
    */
    public function to(string $path): string
    {
        return $this->root . DIRECTORY_SEPARATOR . $this->sanitized($path);
    }


    /**
     * @param string $path
     * @return mixed
    */
    private function sanitized(string $path)
    {
        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, trim($path, '/'));
    }
}