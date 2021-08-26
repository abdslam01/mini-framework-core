<?php

namespace Abdslam01\MiniFrameworkCore\Database;

use Illuminate\Database\Capsule\Manager as Capsule;
use function Abdslam01\MiniFrameworkCore\Helpers\env2;

/**
 * Database
 */
class Database{
    public $capsule, $schema;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(){
        $this->capsule = new Capsule;

        $this->capsule->addConnection([
            'driver' => env2('driver', 'mysql'),
            'host' => env2('host', 'localhost'),
            'database' => env2('database', 'mvc_course'),
            'username' => env2('username', 'root'),
            'password' => env2('password', ''),
            'charset' => env2('charset', 'utf8'),
            'collation' => env2('collation', 'utf8_unicode_ci'),
            'prefix' => env2('prefix', ''),
        ]);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
    
    /**
     * init
     *
     * @return void
     */
    public static function init(){
        new Database();
    }
}