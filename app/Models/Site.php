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
        'category_id',
    ];

    public function category(): BelongsTo
{
    return $this->belongsTo(Category::class, 'category_id');
}
}

?>
