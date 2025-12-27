<?php

namespace App\Domain\Tasks\Entities;

class TaskComment
{
    public function __construct(
        public ?int $id,
        public int $taskId,
        public string $content,
        public ?int $userId,
        public \DateTime $createdAt,
    ) {}
}
