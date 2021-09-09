<?php

namespace Abdslam01\MiniFrameworkCore\Helpers;

use Abdslam01\MiniFrameworkCore\Session;

/**
 * SessionHelpers
 */
class SessionHelpers{
    private static ?Session $session = null;
    
    /**
     * session
     *
     * @return Session
     */
    public static function session(): Session{
        if(!self::$session)
            self::$session = new Session;
        return self::$session;
    }
}