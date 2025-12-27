<?php
namespace App\Domain\Tasks\Repositories;

use App\Models\CaTask as TaskModel;
use App\Domain\Tasks\Entities\Task;

class EloquentTaskRepository implements TaskRepository
{
    public function all(): array
    {
        return TaskModel::latest()->get()
            ->map(fn ($m) => $this->toEntity($m))
            ->toArray();
    }

    public function find(int $id): ?Task
    {
        $m = TaskModel::find($id);
        return $m ? $this->toEntity($m) : null;
    }

    public function store(Task $task): Task
    {
        $model = TaskModel::create([
            'titre'          => $task->titre,
            'description'    => $task->description,
            'responsables'   => $task->responsables,
            'commentaire'    => $task->commentaire,
            'est_terminee'   => $task->estTerminee,
            'est_archivee'   => $task->estArchivee,
            'date_effectuee' => $task->dateEffectuee,
        ]);

        return $this->toEntity($model);
    }

    public function update(Task $task): Task
    {
        $model = TaskModel::findOrFail($task->id);

        $model->update([
            'titre'          => $task->titre,
            'description'    => $task->description,
            'responsables'   => $task->responsables,
            'commentaire'    => $task->commentaire,
            'est_terminee'   => $task->estTerminee,
            'est_archivee'   => $task->estArchivee,
            'date_effectuee' => $task->dateEffectuee,
        ]);

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        TaskModel::where('id', $id)->delete();
    }

    private function toEntity(TaskModel $m): Task
    {
        return new Task(
            id: $m->id,
            titre: $m->titre,
            description: $m->description,
            responsables: $m->responsables,
            commentaire: $m->commentaire,
            estTerminee: $m->est_terminee,
            estArchivee: $m->est_archivee,
            dateEffectuee: $m->date_effectuee,
            dateCreation: $m->created_at,
        );
    }
}
