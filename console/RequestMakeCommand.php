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
class RequestMakeCommand extends Command
{
    protected $commandName = 'make:request';
    protected $commandDescription = "Create a request";

    protected $commandArgumentName = "requestName";
    protected $commandArgumentDescription = "the request name"; 
    
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

        $fullRequestName = "./app/https/".$name;
        $RequestName = preg_replace("#^(.*)(/)([^/]+)$#", "$3", $name);
        $namespace = strpos($name, "/") 
            ? str_replace("/", "\\", preg_replace("#^(.*)(\/)([^/]+)$#", "$1", $name)) : "";
        $directories = preg_replace("#^(.*)(/)([^/]+)$#", "$1", $fullRequestName);

        try{
            if(!file_exists($directories))
                mkdir($directories, 0777, true);
            
            $file = fopen("$fullRequestName.php", "w+");
            fwrite($file, "<?php

namespace App\https".(!empty($namespace)?'\\':'')."$namespace;

use Abdslam01\MiniFrameworkCore\Https\HttpRequest;

class $RequestName extends HttpRequest {
    
}
");
            fclose($file);
            $output->writeln("<info>$fullRequestName.php is created succeessfully!<info>");
        }catch(Exception $e){
            $output->writeln("<error>File Not Created: $e</error>");
        }
        return 0;
    }
}