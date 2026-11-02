<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, $default = null): ?string
    {
        try {
            $setting = static::where('key', $key)->first();
            return ($setting && $setting->value !== null && $setting->value !== '') ? $setting->value : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function set(string $key, ?string $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function sanitizePhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        // Hapus karakter selain angka
        $digits = preg_replace('/[^0-9]/', '', $phone);

        // Ubah awalan 0 menjadi 62 (standar format internasional WhatsApp)
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        return $digits;
    }
}
