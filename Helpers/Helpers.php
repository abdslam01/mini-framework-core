<?php

namespace Abdslam01\MiniFrameworkCore\Helpers;

use Abdslam01\MiniFrameworkCore\Https\Response;
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
                if(isset($_ENV["ENV"]))
                    return $_ENV["ENV"][$key] ?? $default;
                throw new Exception("\$_ENV[ENV] is not set");
            }
        }

        if(!function_exists("view")){            
            /**
             * view
             *
             * @param  mixed $view
             * @param  mixed $params
             * @return void
             */
            function view(string $view, array $params = []){
                (new Response())->renderView($view, $params);
            }
        }

        if(!function_exists("database_path")){                        
            /**
             * database_path
             *
             * @param  mixed $db_name
             * @return string
             */
            function database_path(string $db_name = "db"){
                $path = __DIR__."\\..\\..\\app\\database\\".str_replace(".sqlite", "", $db_name).".sqlite";
                if(!file_exists($path))
                    touch($path);
                return $path;
            }
        }
    }
}