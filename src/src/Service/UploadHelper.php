<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadHelper
{
    const AD_IMAGE_PATH = 'uploads';
    const DEFAULT_IMAGE_PATH = 'images/image-placeholder.jpg';

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

    /**
     * @return string
     */
    public function fixtureUpload(File $file): string
    {
        $destination = $this->publicPath . '/' . self::AD_IMAGE_PATH;
        $originalFilename = $file->getFilename();
        $baseFilename = pathinfo($originalFilename, PATHINFO_FILENAME);
        $filename = Urlizer::urlize($baseFilename) . '-' . uniqid() . '.' . $file->guessExtension();

        $fs = new Filesystem();
        $fs->copy($file->getRealPath(), $destination . '/' . $filename, true);

        return $filename;
    }
}