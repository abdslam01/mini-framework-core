<?php

namespace Abdslam01\MiniFrameworkCore;

/**
 * View
 */
class View {    
    /**
     * compileView
     *
     * @param  mixed $path_to_views
     * @param  mixed $params
     * @return void
     */
    public static function compileView(string $path_to_views, array $params){
        $content = file_get_contents($path_to_views);

        // change {{$var}} => <?=$var?\> # yeah, you spelled it write
        $content = preg_replace("{{{([^\}]*)}}}", "<?=$1?>", $content);

        try{
            $final_path_to_views = str_replace("views", "cache", $path_to_views);
            $file = fopen($final_path_to_views, "w+");
            fwrite($file, $content);
            fclose($file);

            extract($params);
            require_once $final_path_to_views;
            die();
        }catch(\Exception $e){
            throw new \Exception("Fail to compile the view.\n$e");
        }
    }
}