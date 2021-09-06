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

        // change {{$var}} => <?=htmlspecialchars($var)?\> # yeah, you spelled it write
        $content = preg_replace("{{{([^\}]*)}}}", "<?=htmlspecialchars($1)?>", $content);

        // change {!!$var!!} => <?=$var?\>
        $content = preg_replace("{{!!([^\}]*)!!}}", "<?=$1?>", $content);

        //change @elseif() ==> <?php }elseif(){ ?\>
        $content = preg_replace("#@elseif(\(.*\))#", "<?php }elseif$1{ ?>", $content);

        // change @foreach($data as $d) ... @endforeach => <?php foreach($data as $d) ?\> ... <?php endforeach ?\>
        // same for: @if @else @endif
        // same as: @for(...) @endfor, @while(...) @endwhile
        $content = preg_replace("#@(\w+)(\(.*\))#", "<?php $1$2{ ?>", $content);
        $content = preg_replace("#@else#", "<?php }else{ ?>", $content);
        $content = preg_replace("#\@(\w+)[^\(]#", "<?php } ?>", $content);

        try{
            $final_path_to_views = str_replace("views", "cache", $path_to_views);
            if (!is_dir($dirname = preg_replace("#[^/]\w+\.php$#", "", $final_path_to_views)))
                mkdir($dirname, 0755, true);
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