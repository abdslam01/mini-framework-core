<?php

namespace Abdslam01\MiniFrameworkCore\Console;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ControllerMakeCommand
 */
class ControllerMakeCommand extends Command
{
    protected $commandName = 'make:controller';
    protected $commandDescription = "Create a controller";

    protected $commandArgumentName = "controllerName";
    protected $commandArgumentDescription = "the controller name"; 
    
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

        $fullControllerName = "./app/controllers/".$name;
        $ControllerName = preg_replace("#^(.*)(/)([^/]+)$#", "$3", $name);
        $namespace = strpos($name, "/") 
            ? str_replace("/", "\\", preg_replace("#^(.*)(\/)([^/]+)$#", "$1", $name)) : "";
        $directories = preg_replace("#^(.*)(/)([^/]+)$#", "$1", $fullControllerName);

        try{
            if(!file_exists($directories))
                mkdir($directories, 0777, true);
            
            $file = fopen("$fullControllerName.php", "w+");
            fwrite($file, "<?php

namespace App\controllers".(!empty($namespace)?'\\':'')."$namespace;

use Abdslam01\MiniFrameworkCore\Controller;

class $ControllerName extends Controller {
    
}
            ");
            fclose($file);
            $output->writeln("<info>$fullControllerName.php is created succeessfully!<info>");
        }catch(Exception $e){
            $output->writeln("<error>File Not Created: $e</error>");
        }
        return 0;
    }
}