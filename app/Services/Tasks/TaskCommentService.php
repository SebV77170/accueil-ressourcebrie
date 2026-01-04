<?php

namespace App\Services\Tasks;

use App\Domain\Tasks\Entities\TaskComment;
use App\Domain\Tasks\Repositories\TaskCommentRepository;

class TaskCommentService
{
    public function __construct(private TaskCommentRepository $repo) {}

    public function add(int $taskId, string $content, ?int $userId = null): TaskComment
    {
        $comment = new TaskComment(
            id: null,
            taskId: $taskId,
            subTaskId: null,
            content: $content,
            userId: $userId,
            createdAt: new \DateTime(),
        );

        return $this->repo->add($comment);
    }

    public function addForSubTask(int $taskId, int $subTaskId, string $content, ?int $userId = null): TaskComment
    {
        $comment = new TaskComment(
            id: null,
            taskId: $taskId,
            subTaskId: $subTaskId,
            content: $content,
            userId: $userId,
            createdAt: new \DateTime(),
        );

        return $this->repo->add($comment);
    }

    public function listForTask(int $taskId)
    {
        return $this->repo->forTask($taskId);
    }

    public function listForSubTask(int $subTaskId)
    {
        return $this->repo->forSubTask($subTaskId);
    }
}
