<?php

namespace App\Service;

use App\Entity\Task;
use DateTimeImmutable;
use DateInterval;

class TaskService
{

    public function canEdit(Task $task): bool
    {
        $createdAt = $task->getCreatedAt();
        if (!$createdAt) {
            return false; 
        }

        $now = new DateTimeImmutable();
        $limit = $createdAt->add(new DateInterval('P1D')); // +1 jour

        return $now <= $limit;
    }
}
