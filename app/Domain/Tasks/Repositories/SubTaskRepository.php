<?php
namespace App\Domain\Tasks\Repositories;

use App\Domain\Tasks\Entities\SubTask;

interface SubTaskRepository
{
    public function forTask(int $taskId): array;
    public function find(int $taskId, int $id): ?SubTask;
    public function store(SubTask $task): SubTask;
    public function update(SubTask $task): SubTask;
    public function delete(int $taskId, int $id): void;
}
