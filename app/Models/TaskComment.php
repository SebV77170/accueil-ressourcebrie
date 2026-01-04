<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $fillable = [
        'task_id',
        'sub_task_id',
        'content',
        'user_id',
    ];

    public function task()
    {
        return $this->belongsTo(CaTask::class, 'task_id');
    }

    public function subTask()
    {
        return $this->belongsTo(CaSubTask::class, 'sub_task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
