<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'user_id'
    ];
    
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categorie_plat');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
