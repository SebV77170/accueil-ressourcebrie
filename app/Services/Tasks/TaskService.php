<?php
namespace App\Services\Tasks;

use App\Domain\Tasks\Entities\Task;
use App\Domain\Tasks\Repositories\TaskRepository;
use App\Domain\Tasks\Repositories\SubTaskRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    public function __construct(
        private TaskRepository $repo,
        private SubTaskRepository $subTaskRepo,
    ) {}

    public function list(
        ?string $status = null,
        int $perPage = 10,
        ?int $page = null,
        ?int $responsableId = null,
    ): LengthAwarePaginator
    {
        $tasks = collect($this->repo->all());

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

            $task->visibleSubTasks = $task->subTasks;
            $task->visibleSubTasksCount = $task->subTasksCount;
            $task->visibleCompletedSubTasksCount = $task->completedSubTasksCount;
        }

        $tasks = $tasks->filter(function ($task) use ($status) {
            return match ($status) {
                'pending' => ! $task->estArchivee && ! $task->estTerminee,
                'completed' => ! $task->estArchivee && $task->estTerminee,
                'archived' => $task->estArchivee,
                default => true,
            };
        })->values();

        if ($responsableId) {
            $tasks = $tasks->filter(function ($task) use ($responsableId) {
                $taskMatch = in_array($responsableId, $task->responsables ?? [], true);
                $subTaskMatch = collect($task->subTasks)
                    ->contains(fn ($subTask) => in_array($responsableId, $subTask->responsables ?? [], true));

                return $taskMatch || $subTaskMatch;
            })->values();

            $tasks = $tasks->map(function ($task) use ($responsableId) {
                $task->visibleSubTasks = array_values(array_filter(
                    $task->subTasks,
                    fn ($subTask) => in_array($responsableId, $subTask->responsables ?? [], true),
                ));
                $task->visibleSubTasksCount = count($task->visibleSubTasks);
                $task->visibleCompletedSubTasksCount = collect($task->visibleSubTasks)
                    ->filter(fn ($subTask) => $subTask->estTerminee)
                    ->count();

                return $task;
            })->values();
        }

        $page = $page ?: LengthAwarePaginator::resolveCurrentPage();
        $paginatedItems = $tasks->forPage($page, $perPage)->values();

        return new LengthAwarePaginator(
            $paginatedItems,
            $tasks->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()],
        );
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
            throw new \RuntimeException("Tâche introuvable.");
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
            throw new \RuntimeException("Tâche introuvable.");
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
            throw new \RuntimeException("Tâche introuvable.");
        }

        $task->archive();

        return $this->repo->update($task);
    }

    public function delete(int $id): void
    {
        $this->repo->delete($id);
    }
}
