<?php

namespace App\Domain\Tasks\Repositories;

use App\Models\TaskComment as CommentModel;
use App\Domain\Tasks\Entities\TaskComment;

class EloquentTaskCommentRepository implements TaskCommentRepository
{
    public function add(TaskComment $comment): TaskComment
    {
        $model = CommentModel::create([
            'task_id' => $comment->taskId,
            'content' => $comment->content,
            'user_id' => $comment->userId,
        ]);

        return $this->toEntity($model);
    }

    public function forTask(int $taskId): array
    {
        return CommentModel::where('task_id', $taskId)
            ->latest()
            ->get()
            ->map(fn ($m) => $this->toEntity($m))
            ->toArray();
    }

    private function toEntity(CommentModel $m): TaskComment
    {
        return new TaskComment(
            id: $m->id,
            taskId: $m->task_id,
            content: $m->content,
            userId: $m->user_id,
            createdAt: $m->created_at,
        );
    }
}
