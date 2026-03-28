<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plat_id',
        'score',
        'warning_message',
        'status',
        'label',
    ];

    protected $casts = [
        'score' => 'integer',
        'warning_message' => 'string',
        'label' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plat()
    {
        return $this->belongsTo(Plat::class);
    }

    public function isRecommended(): bool
    {
        return $this->score >= 50;
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForPlat($query, $platId)
    {
        return $query->where('plat_id', $platId);
    }

    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
