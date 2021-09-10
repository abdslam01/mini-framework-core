<?php

namespace Abdslam01\MiniFrameworkCore\Https;

/**
 * HttpRequest
 */
class HttpRequest {    
    private array $body, $errors;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(){
        $this->body = $this->requestBody();
        $this->errors = [];
    }

    /**
     * requestBody
     *
     * @return array
     */
    private function requestBody(): array{
        $body = [];
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            foreach($_GET as $key=>$_)
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            foreach($_POST as $key=>$_)
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        unset($body['url']);
        return $body;
    }

    /**
     * getBody
     *
     * @return array
     */
    public function getBody(): array{
        return $this->body;
    }

    public function validator(array $rules){
        foreach ($rules as $key => $arrayRules) {
            if(array_key_exists($key, $this->getBody())){
                if(is_string($arrayRules))
                    $arrayRules = explode('|', $arrayRules);
                foreach ($arrayRules as $rule) {
                    switch($rule){
                        case 'required':
                            $this->required($key);
                            break;
                        case substr($rule, 0, 3) === 'max':
                            $this->max($key, $rule);
                            break;
                        case substr($rule, 0, 3) === 'min':
                            $this->min($key, $rule);
                            break;
                    }
                }
            }
        }
        return $this->errors;
    }

    /**
     * required
     *
     * @param  string $key
     * @return void
     */
    private function required(string $key){
        $val = $this->body[$key];
        if(!isset($val) || is_null($val) || empty($val)){
            $this->errors[$key] = "$key is required";
        }
    }

    /**
     * max
     *
     * @param  string $key
     * @param string $rule
     * @return void
     */
    private function max(string $key, string $rule){
        preg_match("#(\d+)#", $rule, $matches);
        $maxLength = intval($matches[0]);
        if(strlen($this->body[$key]) > $maxLength)
            $this->errors[$key] = "$key must contain the number of characters less than or equal to $maxLength";
    }

    /**
     * min
     *
     * @param  string $key
     * @param  string $rule
     * @return void
     */
    private function min(string $key, string $rule){
        preg_match("#(\d+)#", $rule, $matches);
        $minLength = intval($matches[0]);
        if(strlen($this->body[$key]) < $minLength)
            $this->errors[$key] = "$key must contain the number of characters greater than or equal to $minLength";
    }
}