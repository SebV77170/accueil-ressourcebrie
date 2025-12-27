<?php
namespace App\Services\Tasks;

use App\Domain\Tasks\Entities\Task;
use App\Domain\Tasks\Repositories\TaskRepository;

class TaskService
{
    public function __construct(private TaskRepository $repo) {}

    public function list()
    {
        return $this->repo->all();
    }

    public function create(array $data): Task
    {
        $task = new Task(
            id: null,
            titre: $data['titre'],
            description: $data['description'] ?? null,
            responsables: $data['responsables'] ?? [],
            commentaire: $data['commentaire'] ?? null,
            estTerminee: false,
            estArchivee: false,
            dateEffectuee: null,
            dateCreation: new \DateTime(),
        );

        return $this->repo->store($task);
    }

    public function update(int $id, array $data): Task
    {
        $task = $this->repo->find($id);

        if (! $task) {
            throw new \RuntimeException("Task not found");
        }

        $task->titre        = $data['titre']        ?? $task->titre;
        $task->description  = $data['description']  ?? $task->description;
        $task->responsables = $data['responsables'] ?? $task->responsables;
        $task->commentaire  = $data['commentaire']  ?? $task->commentaire;

        return $this->repo->update($task);
    }

    public function toggleComplete(int $id): Task
    {
        $task = $this->repo->find($id);

        if (! $task) {
            throw new \RuntimeException("Task not found");
        }

        $task->estTerminee
            ? $task->uncomplete()
            : $task->complete();

        return $this->repo->update($task);
    }

    public function archive(int $id): Task
    {
        $task = $this->repo->find($id);

        if (! $task) {
            throw new \RuntimeException("Task not found");
        }

        $task->archive();

        return $this->repo->update($task);
    }

    public function delete(int $id): void
    {
        $this->repo->delete($id);
    }
}
