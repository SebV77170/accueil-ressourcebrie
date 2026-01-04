<?php

namespace App\Domain\Tasks\Entities;

class TaskComment
{
    public ?string $userName = null;

    public function __construct(
        public ?int $id,
        public int $taskId,
        public ?int $subTaskId,
        public string $content,
        public ?int $userId,
        public \DateTime $createdAt,
    ) {}
}
