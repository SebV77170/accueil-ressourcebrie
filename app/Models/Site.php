<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';

    protected $fillable = [
        'nom',
        'description',
        'url',
        'categorie',
    ];
}

?>
