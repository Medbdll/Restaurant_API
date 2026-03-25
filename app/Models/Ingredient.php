<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $table = 'ingredient';
    
    protected $fillable = [
        'name',
        'tags',
    ];
    
    protected $casts = [
        'tags' => 'array',
    ];
    public function plats()
    {
        return $this->belongsToMany(Plat::class, 'plat_ingredient');
    }
    
}
