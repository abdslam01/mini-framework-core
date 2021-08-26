<?php

namespace Abdslam01\MiniFrameworkCore;

use ReflectionMethod;
use ReflectionFunction;
use Abdslam01\MiniFrameworkCore\Https\HttpRequest;

/**
 * Request
 */
class Request {
    private $path, $action, $params, $routeName;
    
    /**
     * __construct
     *
     * @param  mixed $path
     * @param  mixed $action
     * @return void
     */
    public function __construct(string $path, $action){
        $this->path = trim($path, '/');
        $this->action = $action;
    }
    
    /**
     * match
     *
     * @param  mixed $url
     * @return bool
     */
    public function match($url){
        $path = preg_replace("#({\w+})#", "([^/]+)", $this->path);
        if(preg_match("#^$path$#", $url, $results)){
            array_shift($results);
            $this->params=$results;
            return true;
        }
        return false;
    }
    
    /**
     * execute
     *
     * @return void
     */
    public function execute(){
        if(is_string($this->action)){
            [$controller, $method] = explode("@", $this->action);
            $controller = "App\\controllers\\".$controller;

            $reflectionMethodParams = (new ReflectionMethod($controller, $method))->getParameters();
            count($reflectionMethodParams) > 0
                ? ($paramClass=$reflectionMethodParams[0]->getClass())
                : ($paramClass=null);

            $paramClass && (
                    ($paramClassName = $paramClass->getName())===HttpRequest::class
                            || $paramClass->getParentClass()->getName()===HttpRequest::class
                    )
                // check if the class of first argument of the class::method is HttpRequest
                // forget ? => check https://stackoverflow.com/questions/2692481/getting-functions-argument-names
                ? (new $controller)->$method($this->httpRequest = new $paramClassName,...$this->params)
                : (new $controller)->$method(...$this->params);

        }else{ // $action is function
            $reflectionFunctionParams = (new ReflectionFunction($this->action))->getParameters();
            count($reflectionFunctionParams) > 0
                ? ($paramClass=$reflectionFunctionParams[0]->getClass())
                : ($paramClass=null);

            $paramClass && (
                        ($paramClassName = $paramClass->getName())===HttpRequest::class
                            || $paramClass->getParentClass()->getName()===HttpRequest::class
                    )
                // check if the class of first argument of the function is HttpRequest
                // forget ? => check https://stackoverflow.com/questions/2692481/getting-functions-argument-names
                ? $this->action->__invoke($this->httpRequest = new $paramClassName, ...$this->params)
                : $this->action->__invoke(...$this->params);
                //or call_user_func_array($this->action, ...$this->params);
        }
    }
    
    /**
     * name
     *
     * @param  mixed $name
     * @return array
     */
    public function name(string $name=null){
        if(!is_null($name))
            $this->routeName[$name] = $this->path;
        return $this->routeName;
    }

    public function getPath(){
        return $this->path;
    }
}