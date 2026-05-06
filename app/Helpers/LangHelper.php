<?php

namespace App\Helpers;

class LangHelper
{
    public static function get($record, string $field, ?string $lang = null): string
    {
        $lang = $lang ?? session('admin_lang', 'en');

        if ($lang === 'en' || empty($lang)) {
            return $record->$field ?? '';
        }

        $langData = $record->$lang ?? null;

        if (is_array($langData) && !empty($langData[$field])) {
            return $langData[$field];
        }

        return $record->$field ?? '';
    }
}