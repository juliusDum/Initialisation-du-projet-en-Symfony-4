<?php

namespace App\Service;


class Slugify
{
    public function generate(string $slug) : string
    {
        $slug = str_replace(' ', '-', $slug);
        $slug = strtolower($slug);
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug);
        $slug = preg_replace("#[^a-z0-9-]*#", "", $slug);
        return $slug;
    }
}