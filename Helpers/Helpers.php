<?php

namespace Abdslam01\MiniFrameworkCore\Helpers;

use Exception;
use Abdslam01\MiniFrameworkCore\Route;

/**
 * Helpers
 */
class Helpers {    
    public static function load(){
        if(!function_exists("route")){            
            /**
             * route
             *
             * @param  mixed $name
             * @param  mixed $params
             * @return void
             */
            function route(string $name, $params=[]){
                return Route::url($name, $params);
            }
        }

        if(!function_exists("redirect")){            
            /**
             * redirect
             *
             * @param  mixed $name
             * @param  mixed $params
             * @return void
             */
            function redirect(string $name, $params=[]){
                $path = Route::url($name, $params);
                header("Location: $path");
            }
        }

        if(!function_exists("env2")){            
            /**
             * env2
             *
             * @param  mixed $key
             * @param  mixed $default
             * @return void
             */
            function env2(string $key, string $default=null){
                if(isset($_ENV["ENV"])){
                    try{
                        if(isset($_ENV["ENV"][$key]))
                            return $_ENV["ENV"][$key];
                        throw new Exception("The environment variable '$key' is not set");
                    }catch(string $e){
                        return $default;
                    }
                }
                throw new Exception("\$_ENV[ENV] is not set");
            }
        }
    }
}