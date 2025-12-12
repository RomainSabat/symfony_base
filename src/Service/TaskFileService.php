<?php
namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


class TaskFileService
{
    private $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function createTask(string $title, string $description): void
    {
        $taskId = uniqid();
        $filePath = 'tasks/' . $taskId . '.txt';
        try {
            $this->filesystem->dumpFile($filePath, $title. "\n" .$description);
        } catch (IOExceptionInterface $exception) {
            // Handle the exception as needed
            throw new \RuntimeException('An error occurred while creating the task file: ' . $exception->getMessage());
        }
    }

    public function listTasks(): array
    {
        $taskFiles = [];
        try {
            $files = scandir('tasks/');
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'txt') {
                    $taskFiles[] = $file;
                }
            }
        } catch (IOExceptionInterface $exception) {
            // Handle the exception as needed
            throw new \RuntimeException('An error occurred while listing task files: ' . $exception->getMessage());
        }
        return $taskFiles;
    }

    public function getTask(string $taskId): ?string
    {
        $filePath = 'tasks/' . $taskId . '.txt';
        try {
            if ($this->filesystem->exists($filePath)) {
                $content = file_get_contents($filePath);
                echo $content;
                return $content;
            }
            return null;
        } catch (IOExceptionInterface $exception) {
            // Handle the exception as needed
            throw new \RuntimeException('An error occurred while reading the task file: ' . $exception->getMessage());
        }
    }

    public function deleteTask(string $taskId): void
    {
        $filePath = 'tasks/' . $taskId . '.txt';
        try {
            if ($this->filesystem->exists($filePath)) {
                $this->filesystem->remove($filePath);
            } else {
                throw new \RuntimeException('Task file does not exist.');
            }
        } catch (IOExceptionInterface $exception) {
            // Handle the exception as needed
            throw new \RuntimeException('An error occurred while deleting the task file: ' . $exception->getMessage());
        }
    }

    public function updateTask(string $taskId, string $title, $description): void
    {
        $filePath = 'tasks/' . $taskId . '.txt';
        try {
            if ($this->filesystem->exists($filePath)) {
                $this->filesystem->dumpFile($filePath, $title. "\n" .$description);
            } else {
                throw new \RuntimeException('Task file does not exist.');
            }
        } catch (IOExceptionInterface $exception) {
            // Handle the exception as needed
            throw new \RuntimeException('An error occurred while updating the task file: ' . $exception->getMessage());
        }
    }

    public function taskFileExists(string $taskId): bool
    {
        $filePath = 'tasks/' . $taskId . '.txt';
        return $this->filesystem->exists($filePath);
    }
}