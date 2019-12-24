<?php
namespace Jan\Components\FileSystem;


/**
 * Class File
 * @package Jan\Components\FileSystem
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
     * @param string $filePath
     * @return bool
     */
      public function exists(string $filePath)
      {
           return file_exists($this->to($filePath));
      }

     /**
      * @param string $location
     */
      public function generate(string $location)
      {

      }


      /**
      * @param string $filePath
     */
      public function remove(string $filePath)
      {
          if($this->exists($filePath))
          {
              unlink($this->to($filePath));
          }
      }

      /**
       * @param string $from
       * @param string $to
      */
      public function move(string $from, string $to)
      {

      }



      /**
       * @param string $filePath
       * @return mixed
      */
      public function load(string $filePath)
      {
          return require $this->to($filePath);
      }

      /**
      * @param string $filePath
      * @return string|string[]
     */
      public function to(string $filePath)
      {
          /* return $this->getRoot() . $this->resolvePath($filePath); */
          return implode('.', [
              $this->getResolvedRoot(),
              $this->resolvePath($filePath)]
          );
      }


      /**
       * @return string
      */
      private function getResolvedRoot()
      {
           return $this->resolveRoot($this->root);
      }


     /**
      * @param string $root
      * @return string
     */
      protected function resolveRoot(string $root)
      {
          return rtrim($root, '/') . DIRECTORY_SEPARATOR;
      }


      /**
       * @param string $path
       * @return string|string[]
      */
      protected function resolvePath(string $path)
      {
          return str_replace(
              ['\\', '/'],
              DIRECTORY_SEPARATOR,
          trim($path, '/')
          );
      }
}