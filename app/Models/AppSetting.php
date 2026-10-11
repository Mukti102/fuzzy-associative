<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    use HasFactory;

    protected $table = 'app_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Get a setting value by key with cache.
     */
    public static function get(string $key, $default = null): ?string
    {
        try {
            $all = static::getAll();
            return $all[$key] ?? $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, ?string $value, string $group = 'general'): self
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        static::clearCache();

        return $setting;
    }

    /**
     * Get all settings as key-value associative array.
     */
    public static function getAll(): array
    {
        return Cache::rememberForever('app_settings_all', function () {
            return static::query()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('app_settings_all');
    }
}
