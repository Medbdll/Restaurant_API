<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
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
    
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'plat_ingredient');
    }
    public function recommendations(){
        return $this->hasMany(Recommendation::class);
    }
}
