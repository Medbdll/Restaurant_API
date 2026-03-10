<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];
    public function plats()
    {
        return $this->hasMany(Plat::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    
}
