<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaTask extends Model
{
    protected $fillable = [
        'category_id',
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

    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'task_id')->latest();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subTasks()
    {
        return $this->hasMany(CaSubTask::class, 'task_id')->latest();
    }

}
