<?php

namespace Abdslam01\MiniFrameworkCore;

use Abdslam01\MiniFrameworkCore\Database\Database;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * Model
 */
class Model extends BaseModel{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(){
        Database::init();
    }
}