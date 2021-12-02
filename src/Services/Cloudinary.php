<?php

namespace App\Services;

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

class Cloudinary
{
    public static function getImage(array $data): array
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dpeadae9w',
                'api_key' => '643832177916977',
                'api_secret' => 'GmOP7mzU6Y4J3bZFf-_Ie3wKdPg'
            ],
            'url' => [
                'secure' => true
            ]
        ]);

        $func = function ($file) {
            $file = (new UploadApi())->upload($file, [
                'folder' => 'images'
            ]);
            return $file['url'];
        };

        $files = array_map($func, $data);
        return $files;
    }
}
