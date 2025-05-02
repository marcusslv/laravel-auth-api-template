<?php

namespace App\Domains\Abstracts;

interface EnumInterface
{
    /**
     * Get translations
     * $locale = 'en'
     *
     * @return array [ 'en' => [], 'pt_BR' => [], 'es' => [] ]
     */
    public static function translations(string $locale = 'en'): array;
}
