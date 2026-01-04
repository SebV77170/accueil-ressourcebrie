<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaSubTask extends Model
{
    protected $fillable = [
        'task_id',
        'titre',
        'description',
        'responsables',
        'commentaire',
        'est_terminee',
        'est_archivee',
        'date_effectuee',
    ];

    protected $casts = [
        'responsables'   => 'array',
        'est_terminee'   => 'boolean',
        'est_archivee'   => 'boolean',
        'date_effectuee' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(CaTask::class, 'task_id');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'sub_task_id')->latest();
    }
}
