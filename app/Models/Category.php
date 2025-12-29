<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'nom',
    ];

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class, 'category_id');
    }
}
