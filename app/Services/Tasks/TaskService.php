<?php
namespace App\Services\Tasks;

use App\Domain\Tasks\Entities\Task;
use App\Domain\Tasks\Repositories\TaskRepository;
use App\Domain\Tasks\Repositories\SubTaskRepository;

class TaskService
{
    public function __construct(
        private TaskRepository $repo,
        private SubTaskRepository $subTaskRepo,
    ) {}

    public function list()
    {
        $tasks = $this->repo->all();

        foreach ($tasks as $task) {
            $task->subTasks = $this->subTaskRepo->forTask($task->id);
            $task->subTasksCount = count($task->subTasks);
            $task->completedSubTasksCount = collect($task->subTasks)
                ->filter(fn ($s) => $s->estTerminee)
                ->count();

            if ($task->subTasksCount > 0) {
                $isComplete = $task->completedSubTasksCount === $task->subTasksCount;

                if ($task->estTerminee !== $isComplete || ($isComplete && ! $task->dateEffectuee)) {
                    $task->estTerminee = $isComplete;
                    $task->dateEffectuee = $isComplete
                        ? ($task->dateEffectuee ?? new \DateTime())
                        : null;

                    $this->repo->update($task);
                }
            }
        }

        return $tasks;
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
        $task->responsables = $data['responsables'] ?? [];
        $task->commentaire  = $data['commentaire']  ?? $task->commentaire;

        return $this->repo->update($task);
    }

    public function toggleComplete(int $id): Task
    {
        $task = $this->repo->find($id);

        if (! $task) {
            throw new \RuntimeException("Task not found");
        }

        $subTasks = $this->subTaskRepo->forTask($id);

        if (count($subTasks) > 0) {
            $task->estTerminee = collect($subTasks)->every(fn ($s) => $s->estTerminee);
            $task->dateEffectuee = $task->estTerminee
                ? ($task->dateEffectuee ?? new \DateTime())
                : null;

            return $this->repo->update($task);
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
