<?php

use Illuminate\Support\Str;

if (! function_exists('current_module')) {
    function current_module(): string
    {
        $path = request()->path();

        if (Str::startsWith($path, 'ecommerce')) {
            return 'Ecommerce';
        } elseif (Str::startsWith($path, 'cms')) {
            return 'CMS';
        }

        return 'Default';
    }
}
