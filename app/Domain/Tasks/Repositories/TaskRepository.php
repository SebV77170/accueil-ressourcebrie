<?php
namespace App\Domain\Tasks\Repositories;

use App\Domain\Tasks\Entities\Task;

interface TaskRepository
{
    public function all(): array;
    public function find(int $id): ?Task;
    public function store(Task $task): Task;
    public function update(Task $task): Task;
    public function delete(int $id): void;
}
