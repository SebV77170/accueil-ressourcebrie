<?php

namespace App\Domain\Tasks\Repositories;

use App\Models\TaskComment as CommentModel;
use App\Domain\Tasks\Entities\TaskComment;
use App\Models\User;


class EloquentTaskCommentRepository implements TaskCommentRepository
{
    public function add(TaskComment $comment): TaskComment
    {
        $model = CommentModel::create([
            'task_id' => $comment->taskId,
            'sub_task_id' => $comment->subTaskId,
            'content' => $comment->content,
            'user_id' => $comment->userId,
        ]);

        return $this->toEntity($model);
    }

    public function forTask(int $taskId): array
    {
        return CommentModel::where('task_id', $taskId)
            ->whereNull('sub_task_id')
            ->latest()
            ->get()
            ->map(fn ($m) => $this->toEntity($m))
            ->toArray();
    }

    public function forSubTask(int $subTaskId): array
    {
        return CommentModel::where('sub_task_id', $subTaskId)
            ->latest()
            ->get()
            ->map(fn ($m) => $this->toEntity($m))
            ->toArray();
    }

    private function toEntity(CommentModel $m): TaskComment
    {
        $comment = new TaskComment(
            id: $m->id,
            taskId: $m->task_id,
            subTaskId: $m->sub_task_id,
            content: $m->content,
            userId: $m->user_id,
            createdAt: $m->created_at,
        );

        // Charger le nom de l'utilisateur si disponible
        if ($m->user_id) {
            $user = User::find($m->user_id);
            $comment->userName = $user?->name;
        }

        return $comment;
    }
}
