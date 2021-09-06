<?php

namespace Abdslam01\MiniFrameworkCore;

/**
 * View
 */
class View {
    public static function compileView(string $path_to_views, array $params){
        try{
            $final_path_to_views = str_replace("views", "cache", $path_to_views);
            $file = fopen($final_path_to_views, "w+");
            fwrite($file, file_get_contents($path_to_views));
            fclose($file);

            extract($params);
            require_once $final_path_to_views;
            die();
        }catch(\Exception $e){
            throw new \Exception("Fail to compile the view.\n$e");
        }
    }
}