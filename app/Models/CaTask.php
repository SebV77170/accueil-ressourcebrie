<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaTask extends Model
{
    protected $fillable = [
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
}
