<?php

namespace App\Helpers;

use App\Models\Locale;

class TranslationsHelper
{
    public static function getTranslationsString($locale, $admin)
    {
        if(!file_exists(resource_path("lang/{$locale}/frontend.json"))) $locale = "en";

        $translations = json_decode(self::translationAllowedTags(file_get_contents(resource_path("lang/{$locale}/frontend.json"))), true);
        if ($admin) {
            $translations['admin'] = json_decode(self::translationAllowedTags(file_get_contents(resource_path("lang/{$locale}/frontend_admin.json"))), true);
        }

        return $translations;
    }

    public static function getLocales()
    {
        return Locale::select(['locale', 'name'])->get();
    }

    public static function translationAllowedTags($string) {
        return strip_tags($string, "<br><table><tr><th><td><a><div><span>");
    }
}
