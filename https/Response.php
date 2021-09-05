<?php

namespace Abdslam01\MiniFrameworkCore\Https;

class Response {
    protected int $code;
    protected string $path_to_views;

    public function __construct(int $code = 200){
        $this->setStatusCode($this->code = $code);
        $this->path_to_views = "../ressources/views/";
        if($code<200 || $code>299) // means that we've got an error
            $this->path_to_views .= "error/";
    }

    public function setStatusCode(int $code){
        http_response_code($code);
    }

    public function renderView(string $view = ""){
        if($this->code<200 || $this->code>299) // means that we've got an error
            echo file_get_contents("$this->path_to_views$this->code.php");
        else
            echo file_get_contents($this->path_to_views.str_replace(".", "/", $view).".php");
        die();
    }
}