<?php

namespace Abdslam01\MiniFrameworkCore;

use Exception;
/**
 * Env
 */
class Env{    
    /**
     * load
     *
     * @return void
     */
    public static function load(){
        $envFilePath = BASE_DIR."/.env";
        if(!file_exists($envFilePath))
            throw new Exception(".env file not found");

        $content = file_get_contents($envFilePath);
        $content = preg_replace("#(\#.*[\n\r]+)#", "", $content); // Delete Comments
        $content = preg_replace("#[\n\r]+#", "\n", $content); // Delete empty lines
        $content = str_replace("=", "= ", $content); // To prevent function explode to return one element in array (line 17)

        foreach(explode("\n", $content) as $line){
            if(strpos($line, "=")===false)
                continue;
            [$key, $value] = explode("=", $line, 2);
            if($key && isset($value))
            $_ENV["ENV"][trim($key)] = trim($value);
        }
    }
}