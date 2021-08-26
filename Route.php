<?php

namespace Abdslam01\MiniFrameworkCore;

use Exception;

/**
 * Route
 */
class Route {
    private static $requests;
        
    /**
     * main
     *
     * @param  mixed $path
     * @param  mixed $action
     * @param  mixed $request_method
     * @param  mixed $multiple
     * @return Request
     */
    private static function main(string $path, $action, string $request_method, bool $multiple=false){
        $request = new Request($path, $action);
        
        if($multiple)
            foreach(explode(" ", "GET POST PUT PATCH DELETE OPTIONS") as $r_method)
                self::$requests[$r_method][] = $request;
        else
            self::$requests[$request_method][] = $request;

        return $request;
    }
    
    /**
     * get
     *
     * @param  mixed $path
     * @param  mixed $action
     * @return Request
     */
    public static function get(string $path, $action){
        return self::main($path, $action, "GET");
    }
        
    /**
     * post
     *
     * @param  mixed $path
     * @param  mixed $action
     * @return Request
     */
    public static function post(string $path, $action){
        return self::main($path, $action, "POST");
    }
    
    /**
     * put
     *
     * @param  mixed $path
     * @param  mixed $action
     * @return Request
     */
    public static function put(string $path, $action){
        return self::main($path, $action, "PUT");
    }
    
    /**
     * patch
     *
     * @param  mixed $path
     * @param  mixed $action
     * @return Request
     */
    public static function patch(string $path, $action){
        return self::main($path, $action, "PATCH");
    }
    
    /**
     * delete
     *
     * @param  mixed $path
     * @param  mixed $action
     * @return Request
     */
    public static function delete(string $path, $action){
        return self::main($path, $action, "DELETE");
    }
    
    /**
     * options
     *
     * @param  mixed $path
     * @param  mixed $action
     * @return Request
     */
    public static function options(string $path, $action){
        return self::main($path, $action, "OPTIONS");
    }
        
    /**
     * all
     *
     * @param  mixed $path
     * @param  mixed $action
     * @return Request
     */
    public static function all(string $path, $action){
        return self::main($path, $action, "", true);
    }
    
    /**
     * run
     *
     * @return void
     */
    public static function run(){
        foreach(self::$requests[$_SERVER['REQUEST_METHOD']]??[] as $request){
            if($request->match(trim($_GET['url'], '/'))){
                $request->execute();
                die();
            }
        }
        header('HTTP/1.0 404 Not Found');
    }
    
    /**
     * url
     *
     * @param  mixed $name
     * @param  mixed $params
     * @return string|null
     */
    public static function url(string $name, $params=[]){
        foreach(self::$requests as $requests){
            foreach($requests as $request){
                if(array_key_exists($name, $request->name()??[])){
                    $path = $request->getPath();
                    foreach($params as $key=>$value){
                        $path = str_replace("{{$key}}", $value, $path);
                    }
                    break;
                }
            }
        }
        if(!isset($path)) throw new Exception("No route has the name: $name");
        return BASE_URL.$path;
    }
    
    /**
     * loadRoutes
     *
     * @return void
     */
    public static function loadRoutes(){
        $files = scandir("../route/");
        foreach($files as $file){
            if(substr($file, -4)===".php")
                require_once "../route/$file";
        }
    }
}