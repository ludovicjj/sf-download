<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    const ARTICLE_FILE_DIR = 'article';
    public function __construct(
        private readonly Filesystem $localFilesystem
    )
    {
    }

    public function uploadArticleFile(UploadedFile $uploadedFile): string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = Urlizer::urlize($originalFilename) .'-'. uniqid() .'.'. $uploadedFile->guessExtension();

        $this->localFilesystem->write(
            self::ARTICLE_FILE_DIR . '/' . $newFilename,
            file_get_contents($uploadedFile->getPathname())
        );

        return $newFilename;
    }
}