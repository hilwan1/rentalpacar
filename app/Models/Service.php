<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'talent_id',
        'service_name',
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

    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'service_id');
    }
}

