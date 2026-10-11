<?php

use App\Models\AppSetting;

if (!function_exists('setting')) {
    /**
     * Helper to get application/institution setting.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return AppSetting::get($key, $default);
    }
}

if (!function_exists('setting_logo_url')) {
    /**
     * Get institutional logo URL or null.
     */
    function setting_logo_url(): ?string
    {
        $logo = setting('logo_path');
        if ($logo && file_exists(public_path($logo))) {
            return asset($logo);
        }
        return null;
    }
}

if (!function_exists('setting_favicon_url')) {
    /**
     * Get institutional favicon URL with smart fallbacks (favicon -> logo -> default SVG).
     */
    function setting_favicon_url(): string
    {
        $favicon = setting('favicon_path');
        if ($favicon && file_exists(public_path($favicon))) {
            return asset($favicon);
        }

        $logo = setting('logo_path');
        if ($logo && file_exists(public_path($logo))) {
            return asset($logo);
        }

        return asset('favicon.svg');
    }
}
