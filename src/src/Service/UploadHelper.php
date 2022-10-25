<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadHelper
{
    const AD_IMAGE_PATH = 'uploads';
    const DEFAULT_IMAGE_PATH = 'images/nike_air_force_1.jpg';

    public string $publicPath;

    public function __construct(string $publicPath)
    {
        $this->publicPath = $publicPath;
    }

    /**
     * @return string
     */
    public function uploadAdImage(UploadedFile $file): string
    {
        $destination = $this->publicPath . '/' . self::AD_IMAGE_PATH;
        $originalFilename = $file->getClientOriginalName();
        $baseFilename = pathinfo($originalFilename, PATHINFO_FILENAME);
        $filename = Urlizer::urlize($baseFilename) . '-' . uniqid() . '.' . $file->guessExtension();
        $file->move($destination, $filename);

        return $filename;
    }
}