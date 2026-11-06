<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public const CATEGORY_BASIC = 'basic';
    public const CATEGORY_PREMIUM = 'premium';

    protected $fillable = [
        'talent_id',
        'service_name',
        'category',
        'description',
        'duration',
        'price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function isPremium(): bool
    {
        return strtolower($this->category ?? '') === self::CATEGORY_PREMIUM;
    }

    public function isBasic(): bool
    {
        return ! $this->isPremium();
    }

    public function getCategoryLabelAttribute(): string
    {
        return $this->isPremium() ? 'Premium' : 'Basic';
    }

    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'service_id');
    }
}

