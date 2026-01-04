<?php

namespace App\Domain\Tasks\Repositories;

use App\Domain\Tasks\Entities\TaskComment;

interface TaskCommentRepository
{
    public function add(TaskComment $comment): TaskComment;

    public function forTask(int $taskId): array;

    public function forSubTask(int $subTaskId): array;
}
