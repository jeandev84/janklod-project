<?php

/**
 * Class InvalidMethod
 */
class InvalidMethod extends \Exception {}

/**
 * Trait ControllerService
 */
trait ControllerService
{
    /**
     * @param $method
     * @param $arguments
     * @throws InvalidMethod
     */
    public function __call($method, $arguments)
    {
        $method = $method . 'Action';
        if(method_exists($this, $method))
        {
            if($this->before() !== false)
            {
                call_user_func_array([$this, $method], $arguments);
                $this->after();
            }
        }else{

            throw new InvalidMethod(sprintf('Method (%s) not found in Controller (%s)', $method, get_called_class()));
        }
    }
}


/**
 * Class Controller
 */
abstract class Controller
{

    use ControllerService;
    protected function before() {}
    protected function after() {}

}

/*
$controller = new Controller();
$controller->before();
*/


/**
 * Class FrontController
 */
class FrontController extends Controller
{

    public function before()
    {
        echo __METHOD__.'<br>';
    }


    public function indexAction()
    {
        echo __METHOD__. '<br>';
    }
}


$controller = new FrontController();
$controller->index();
die;

/*
In this case don't work
$controller->indexAction();
*/


/**
 * Class AdminController
 */
class AdminController extends Controller
{

    public function before()
    {
        echo __METHOD__.'<br>';
    }


    public function indexAction()
    {
        echo __METHOD__. '<br>';
    }
}


$controller = new AdminController();
$controller->index();
