<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use App\Enum\Role;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;

final class TaskVoter extends Voter
{
    public const EDIT = 'TASK_EDIT';
    public const VIEW = 'TASK_VIEW';
    public const DELETE = 'TASK_DELETE';
       protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }
    protected function voteOnAttribute(string $attribute, mixed $task, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof user) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match($attribute) {
            self::VIEW => $this->canView($task, $user),
            self::EDIT => $this->canEdit($task, $user, $vote),
            self::DELETE => $this->canDelete($task, $user, $vote),
            default => throw new \LogicException('This code should not be reached!')
        };

    }

    public function canEdit(Task $task, User $user, ?Vote $vote): bool
    {
        // Example logic: only the author can edit the task
        if ($user === $task->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles(), true)
) {
            return true;
        }

        $vote?->addReason(sprintf(
            'The logged in user (username: %s) is not the author of this post (id: %d).',
            $user->getEmail(), $task->getId()
        ));

        return false;
    }

    public function canView(Task $task, User $user): bool
    {
        // Example logic: all authenticated users can view the task
        return $user === $task->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles(), true);

    }

    public function canDelete(Task $task, User $user, ?Vote $vote): bool
    {
        // Example logic: only the author can delete the task
        if( in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

         $vote?->addReason(sprintf(
            'The logged in user (username: %s) is not the author of this post (id: %d).',
            $user->getEmail(), $task->getId()
        ));

        return false;

    }
}
