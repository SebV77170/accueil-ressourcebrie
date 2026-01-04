<?php
namespace App\Domain\Tasks\Repositories;

use App\Domain\Tasks\Entities\SubTask;
use App\Domain\Tasks\Entities\TaskComment;
use App\Models\CaSubTask as SubTaskModel;
use App\Models\TaskComment as CommentModel;
use App\Models\User;

class EloquentSubTaskRepository implements SubTaskRepository
{
    public function forTask(int $taskId): array
    {
        return SubTaskModel::where('task_id', $taskId)
            ->latest()
            ->get()
            ->map(fn ($m) => $this->toEntity($m))
            ->toArray();
    }

    public function find(int $taskId, int $id): ?SubTask
    {
        $model = SubTaskModel::where('task_id', $taskId)
            ->where('id', $id)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function store(SubTask $task): SubTask
    {
        $model = SubTaskModel::create([
            'task_id'        => $task->taskId,
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

    public function update(SubTask $task): SubTask
    {
        $model = SubTaskModel::where('task_id', $task->taskId)
            ->where('id', $task->id)
            ->firstOrFail();

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

    public function delete(int $taskId, int $id): void
    {
        SubTaskModel::where('task_id', $taskId)->where('id', $id)->delete();
    }

    private function toEntity(SubTaskModel $m): SubTask
    {
        $task = new SubTask(
            id: $m->id,
            taskId: $m->task_id,
            titre: $m->titre,
            description: $m->description,
            responsables: $m->responsables,
            commentaire: $m->commentaire,
            estTerminee: $m->est_terminee,
            estArchivee: $m->est_archivee,
            dateEffectuee: $m->date_effectuee,
            dateCreation: $m->created_at,
        );

        $task->comments = CommentModel::where('sub_task_id', $m->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($c) {
                $comment = new TaskComment(
                    id: $c->id,
                    taskId: $c->task_id,
                    subTaskId: $c->sub_task_id,
                    content: $c->content,
                    userId: $c->user_id,
                    createdAt: $c->created_at,
                );

                if ($c->user_id) {
                    $user = User::find($c->user_id);
                    $comment->userName = $user?->name;
                }

                return $comment;
            })
            ->toArray();

        $task->commentsCount = count($task->comments);

        return $task;
    }
}
