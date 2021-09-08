<?php

namespace Abdslam01\MiniFrameworkCore\Https;

/**
 * HttpRequest
 */
class HttpRequest {    
    /**
     * getBody
     *
     * @return array
     */
    public function getBody(): array{
        $body = [];
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            foreach($_GET as $key=>$_)
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            foreach($_POST as $key=>$_)
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $body;
    }
}