<?php

namespace Abdslam01\MiniFrameworkCore\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * MigrationExecuteCommand
 */
class MigrationExecuteCommand extends Command
{
    protected $commandName = 'migrate';
    protected $commandDescription = "Migration the database up [or down]";

    protected $upCommandOptionName = "up"; //specified like "php cmd migrate --up" => --up optional
    protected $upCommandOptionDescription = 'If set or If no option, it will execute up() in migrations';

    protected $downCommandOptionName = "down"; //specified like "php cmd migrate down"
    protected $downCommandOptionDescription = 'If set, it will execute down() in migrations';
    
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
            ->addOption(
               $this->upCommandOptionName,
               "u",
               InputOption::VALUE_NONE,
               $this->upCommandOptionDescription
            )
            ->addOption(
                $this->downCommandOptionName,
                "d",
                InputOption::VALUE_NONE,
                $this->downCommandOptionDescription
            )
        ;
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
        $files = scandir("./app/database/migrations");
        foreach($files??[] as $file){
            if(substr($file, -4)===".php")
                $migrationFiles[] = $file;
        }
        unset($files);

        if($input->getOption($this->downCommandOptionName)){ // --down
            rsort($migrationFiles);
            foreach($migrationFiles as $file){
                $pathFile = "App\\database\\migrations\\$file";
                $pathFile = str_replace(".php", "", $pathFile);
                (new $pathFile())->down();
                $output->writeln("table deleted: <info>migration $file successfully executed.</info>");
            }

        }elseif($input->getOption($this->upCommandOptionName)
                || !($input->getOption($this->downCommandOptionName)
                        && $input->getOption($this->upCommandOptionName))
            ){ // --up or (no argument)
                foreach($migrationFiles as $file){
                    sort($migrationFiles);
                    $pathFile = "App\\database\\migrations\\$file";
                    $pathFile = str_replace(".php", "", $pathFile);
                    (new $pathFile())->up();
                    $output->writeln("table created: <info>migration $file successfully executed.</info>");
                }
        }
        
        return 0;
    }
}