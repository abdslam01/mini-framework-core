<?php

namespace Abdslam01\MiniFrameworkCore;

use function Abdslam01\MiniFrameworkCore\Helpers\env2;

class Session {
    protected static $FLASH_KEY;

    public function __construct(){
        self::$FLASH_KEY = env2("APP_KEY", "flash_messages_");

        session_start();
        $flashMessages = $_SESSION[self::$FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::$FLASH_KEY] = $flashMessages;
    }

    public function setFlash($key, $message){
        $_SESSION[self::$FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key){
        return $_SESSION[self::$FLASH_KEY][$key]['value'] ?? false;
    }

    public function hasFlash($key){
        return array_key_exists($key, $_SESSION[self::$FLASH_KEY]);
    }

    public function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public function get($key){
        return $_SESSION[$key] ?? false;
    }

    public function remove($key){
        unset($_SESSION[$key]);
    }

    public function __destruct(){
        $this->removeFlashMessages();
    }

    private function removeFlashMessages(){
        $flashMessages = $_SESSION[self::$FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => $flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::$FLASH_KEY] = $flashMessages;
    }
}