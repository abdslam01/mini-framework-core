<?php

namespace Abdslam01\MiniFrameworkCore\Console;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ModelMakeCommand
 */
class ModelMakeCommand extends Command
{
    protected $commandName = 'make:model';
    protected $commandDescription = "Create a model";

    protected $commandArgumentName = "controllerName";
    protected $commandArgumentDescription = "the model name"; 
    
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
        $name = $input->getArgument($this->commandArgumentName);
        $name = str_replace(".", "/", $name);

        $fullModelName = "./app/models/".$name;
        $ModelName = preg_replace("#^(.*)(/)([^/]+)$#", "$3", $name);
        $namespace = strpos($name, "/") 
            ? str_replace("/", "\\", preg_replace("#^(.*)(\/)([^/]+)$#", "$1", $name)) : "";
        $directories = preg_replace("#^(.*)(/)([^/]+)$#", "$1", $fullModelName);

        try{
            if(!file_exists($directories))
                mkdir($directories, 0777, true);
            
            $file = fopen("$fullModelName.php", "w+");
            fwrite($file, "<?php

namespace App\controllers".(!empty($namespace)?'\\':'')."$namespace;

use Abdslam01\MiniFrameworkCore\Model;

class $ModelName extends Model {
    
}
");
            fclose($file);
            $output->writeln("<info>$fullModelName.php is created succeessfully!<info>");
        }catch(Exception $e){
            $output->writeln("<error>File Not Created: $e</error>");
        }
        return 0;
    }
}