<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\TaskFileService;

#[AsCommand(
    name: 'TaskCommand',
    description: 'Gestion des tâches via la console',
)]
class TaskCommand extends Command
{

    protected static string $defaultName = 'app:task';
    private TaskFileService $taskFileService;
    public function __construct(TaskFileService $taskFileService)
    {
        parent::__construct();
        $this->taskFileService = $taskFileService;
    }

    protected function configure(): void
    {
           $this
        ->setDescription('Gestion des tâches via la console')
        ->addArgument('action', InputArgument::REQUIRED, 'Action à effectuer (create, update, list, get, delete)')
        ->addOption('title', null, InputOption::VALUE_OPTIONAL, 'Titre de la tâche')
        ->addOption('description', null, InputOption::VALUE_OPTIONAL, 'Description de la tâche')
        ->addOption('id', null, InputOption::VALUE_OPTIONAL, 'ID de la tâche');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $action = $input->getArgument('action');

        if ($action) {
            match ($action) {
                'create' => 
                    $this->taskFileService->createTask(
                        $input->getOption('title'),
                        $input->getOption('description')
                    ),
                'update' => 
                    $this->taskFileService->updateTask(
                        $input->getOption('id'),
                        $input->getOption('title'),
                        $input->getOption('description')
                    ),
                'list' => 
                    $this->taskFileService->listTasks(),
                'get' => 
                    $this->taskFileService->getTask($input->getOption('id')),
                'delete' => 
                $this->taskFileService->deleteTask($input->getOption('id')),

                default => $io->error('Action inconnue : ' . $action),
            };
            $io->note(sprintf('You passed an argument: %s', $action));
        };

        //if ($input->getOption('title')) {
            // ...
       // }

        //$io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
