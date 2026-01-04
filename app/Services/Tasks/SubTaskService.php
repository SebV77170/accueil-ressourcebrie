<?php
namespace App\Services\Tasks;

use App\Domain\Tasks\Entities\SubTask;
use App\Domain\Tasks\Repositories\SubTaskRepository;
use App\Domain\Tasks\Repositories\TaskRepository;

class SubTaskService
{
    public function __construct(
        private SubTaskRepository $repo,
        private TaskRepository $taskRepo,
    ) {}

    public function create(int $taskId, array $data): SubTask
    {
        $task = new SubTask(
            id: null,
            taskId: $taskId,
            titre: $data['titre'],
            description: $data['description'] ?? null,
            responsables: $data['responsables'] ?? [],
            commentaire: $data['commentaire'] ?? null,
            estTerminee: false,
            estArchivee: false,
            dateEffectuee: null,
            dateCreation: new \DateTime(),
        );

        $subTask = $this->repo->store($task);

        $this->refreshTaskCompletion($taskId);

        return $subTask;
    }

    public function update(int $taskId, int $id, array $data): SubTask
    {
        $task = $this->repo->find($taskId, $id);

        if (! $task) {
            throw new \RuntimeException("Sous-tâche introuvable");
        }

        $task->titre        = $data['titre']        ?? $task->titre;
        $task->description  = $data['description']  ?? $task->description;
        $task->responsables = $data['responsables'] ?? $task->responsables;
        $task->commentaire  = $data['commentaire']  ?? $task->commentaire;

        $updated = $this->repo->update($task);
        $this->refreshTaskCompletion($taskId);

        return $updated;
    }

    public function toggleComplete(int $taskId, int $id): SubTask
    {
        $task = $this->repo->find($taskId, $id);

        if (! $task) {
            throw new \RuntimeException("Sous-tâche introuvable");
        }

        $task->estTerminee
            ? $task->uncomplete()
            : $task->complete();

        $updated = $this->repo->update($task);
        $this->refreshTaskCompletion($taskId);

        return $updated;
    }

    public function archive(int $taskId, int $id): SubTask
    {
        $task = $this->repo->find($taskId, $id);

        if (! $task) {
            throw new \RuntimeException("Sous-tâche introuvable");
        }

        $task->archive();

        $updated = $this->repo->update($task);
        $this->refreshTaskCompletion($taskId);

        return $updated;
    }

    public function delete(int $taskId, int $id): void
    {
        $this->repo->delete($taskId, $id);
        $this->refreshTaskCompletion($taskId);
    }

    public function listForTask(int $taskId): array
    {
        return $this->repo->forTask($taskId);
    }

    private function refreshTaskCompletion(int $taskId): void
    {
        $task = $this->taskRepo->find($taskId);

        if (! $task) {
            return;
        }

        $subTasks = $this->repo->forTask($taskId);

        $hasSubTasks = count($subTasks) > 0;
        $allComplete = $hasSubTasks && collect($subTasks)->every(fn ($s) => $s->estTerminee);

        $task->estTerminee = $allComplete;
        $task->dateEffectuee = $allComplete
            ? ($task->dateEffectuee ?? new \DateTime())
            : null;

        $this->taskRepo->update($task);
    }
}
