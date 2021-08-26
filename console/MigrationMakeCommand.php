<?php

namespace Abdslam01\MiniFrameworkCore\Console;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * MigrationMakeCommand
 */
class MigrationMakeCommand extends Command
{
    protected $commandName = 'make:migration';
    protected $commandDescription = "Create a migration";

    protected $commandArgumentName = "migrationTableName";
    protected $commandArgumentDescription = "the migration name"; 
    
    /**
     * configure
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::REQUIRED,
                $this->commandArgumentDescription
            );
    }
    
    /**
     * execute
     *
     * @param  mixed $input
     * @param  mixed $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tableName = $input->getArgument($this->commandArgumentName);
        $tableName = preg_replace("#[\.\\/]#", "_", $tableName);

        $MigrationName = "m".time()."_$tableName";
        $fullMigrationName = "./app/database/migrations/".$MigrationName;

        try{
            $file = fopen("$fullMigrationName.php", "w+");
            fwrite($file, "<?php

namespace App\database\migrations;

use Abdslam01\MiniFrameworkCore\Migration;
use Illuminate\Database\Schema\Blueprint;

class $MigrationName extends Migration{
    public function up(){
        \$this->db->schema->dropIfExists('${tableName}s');
        \$this->db->schema->create('${tableName}s', function (Blueprint \$table) {
            \$table->id();
            \$table->timestamps();
        });
    }

    public function down(){
        \$this->db->schema->dropIfExists('${tableName}s');
    }
}
");
            fclose($file);
            $output->writeln("<info>$fullMigrationName.php is created succeessfully!<info>");
        }catch(Exception $e){
            $output->writeln("<error>File Not Created: $e</error>");
        }
        return 0;
    }
}