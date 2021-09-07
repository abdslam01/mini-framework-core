<?php

namespace Abdslam01\MiniFrameworkCore\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ProjectServeCommand
 */
class ProjectServeCommand extends Command
{
    protected $commandName = 'serve';
    protected $commandDescription = "Serve the project for development purpose";

    protected $CommandOptionName = "port"; //specified like "php cmd serve --port <port>" => --port optional
    protected $CommandOptionDescription = 'Run PHP builtin server for the project';
    
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
               $this->CommandOptionName,
               "p",
               InputOption::VALUE_REQUIRED,
               $this->CommandOptionDescription
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
        if(\System("php -S localhost:".($input->getOption($this->CommandOptionName)??"8080"). " server.php")===false)
            $output->writeln("<error>Fail to run server, please try with on other port</error>");     
        return 0;
    }
}