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
        if(!file_exists($envFilePath=__DIR__."\\..\\.env"))
            throw new Exception(".env file not found");

        $content = file_get_contents($envFilePath);
        $content = preg_replace("#([\#;].*([\n\r]+|$))#", "", $content); // Delete Comments [comment starts by either: # or ;]
        $content = preg_replace("#[\n\r]+#", "\n", $content); // Delete empty lines
        $content = str_replace("=", "= ", $content); // To prevent function explode to return one element in array

        foreach(explode("\n", $content) as $line){
            if(strpos($line, "=")===false)
                continue;
            [$key, $value] = explode("=", $line, 2);
            if($key && isset($value))
            $_ENV["ENV"][trim($key)] = trim($value);
        }
    }
}