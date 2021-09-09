<?php

namespace Abdslam01\MiniFrameworkCore\Database;

use Abdslam01\MiniFrameworkCore\Model;

class MigrationTable extends Model {

    private Database $db;

    public function __construct(){
        $this->db = new Database;
        $this->table = "migrations";
    }
    /**
     * createMigrationTable
     *
     * @param  bool $drop_if_exists
     * @return bool
     */
    public function createMigrationTable(bool $drop_if_exists = false){
        if($drop_if_exists)
            $this->db->schema->dropIfExists('migrations');

        if(!$this->db->schema->hasTable("migrations")){
            $this->db->schema->create('migrations', function ($table) {
                $table->id();
                $table->string('migration');
                $table->timestamps();
            });
            return true;
        }
        return false;
    }
    
    /**
     * dropMigrationTable
     *
     * @return void
     */
    public function dropMigrationTable(){
        $this->db->schema->dropIfExists('migrations');
    }
    
    /**
     * getAppliedMigration
     *
     * @return array
     */
    public function getAppliedMigrations(): array{
        return array_map(
            fn($p)=>$p['migration'], 
            MigrationTable::select("migration")->get()->toArray()
        );
    }
}