<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'talent_id',
        'service_id',
        'customer_name',
        'customer_whatsapp',
        'booking_schedule',
        'notes',
        'service_name_snapshot',
        'price_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'booking_schedule' => 'datetime',
            'price_snapshot' => 'integer',
        ];
    }

    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}

