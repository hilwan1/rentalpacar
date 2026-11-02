<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'talent_id',
        'customer_alias',
        'rating',
        'content',
        'screenshot_path',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }

    public function getScreenshotUrlAttribute(): ?string
    {
        if (!$this->screenshot_path) {
            return null;
        }

        if (str_starts_with($this->screenshot_path, 'http://') || str_starts_with($this->screenshot_path, 'https://')) {
            return $this->screenshot_path;
        }

        return asset('storage/' . $this->screenshot_path);
    }
}

