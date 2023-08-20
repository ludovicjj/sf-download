<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    const ARTICLE_IMAGE_DIR = 'article/images';

    const ARTICLE_EXCEL_DIR = 'article/excels';

    const ARTICLE_REFERENCE_DIR = 'article/reference';

    const POST_IMAGE_DIR = 'post/images';


    public function __construct(
        private readonly Filesystem $publicUploadsFilesystem,
        private readonly Filesystem $privateUploadsFilesystem,
        private readonly Filesystem $s3UploadsFilesystem,
        private readonly RequestStackContext $requestStackContext,
        private readonly string $uploadBaseUrl
    )
    {
    }

    /**
     * @throws FilesystemException
     */
    public function uploadArticleImageFile(UploadedFile $uploadedFile, ?string $existingFilename): string
    {
        $newFilename = $this->getNewFilename($uploadedFile);

        $this->downloadFile($uploadedFile, $newFilename, self::ARTICLE_IMAGE_DIR, true);
        $this->deleteExistingFile($existingFilename, self::ARTICLE_IMAGE_DIR);

        return $newFilename;
    }

    /**
     * @throws FilesystemException
     */
    public function uploadArticleExcelFile(UploadedFile $uploadedFile, ?string $existingFilename): string
    {
        $newFilename = $this->getNewFilename($uploadedFile);
        $this->downloadFile($uploadedFile, $newFilename, self::ARTICLE_EXCEL_DIR, true);
        $this->deleteExistingFile($existingFilename, self::ARTICLE_EXCEL_DIR);

        return $newFilename;
    }

    /**
     * @throws FilesystemException
     */
    public function uploadArticleReference(UploadedFile $uploadedFile): string
    {
        $newFilename = $this->getNewFilename($uploadedFile);
        $this->downloadFile($uploadedFile, $newFilename, self::ARTICLE_REFERENCE_DIR, false);

        return $newFilename;
    }

    public function uploadS3(UploadedFile $uploadedFile): string
    {
        $newFilename = $this->getNewFilename($uploadedFile);
        $this->downloadS3($uploadedFile, $newFilename, self::POST_IMAGE_DIR);

        return $newFilename;
    }

    /**
     * @return resource
     * @throws FilesystemException
     */
    public function readStream(string $path, bool $isPublic)
    {
        $filesystem = $isPublic ? $this->publicUploadsFilesystem : $this->privateUploadsFilesystem;
        return $filesystem->readStream($path);
    }

    /**
     * @throws FilesystemException
     */
    public function deleteFile(string $path, bool $isPublic): void
    {
        $filesystem = $isPublic ? $this->publicUploadsFilesystem : $this->privateUploadsFilesystem;
        $filesystem->delete($path);
    }

    // used in Twig function
    public function getImagePath(string $path): string
    {
        return $this->requestStackContext->getBasePath() . $this->uploadBaseUrl . '/' . $path;
    }

    private function getNewFilename(UploadedFile $uploadedFile): string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        return Urlizer::urlize($originalFilename) .'-'. uniqid() .'.'. $uploadedFile->guessExtension();
    }

    /**
     * @throws FilesystemException
     */
    private function downloadFile(UploadedFile $uploadedFile, string $newFilename, string $path, bool $isPublic): void
    {
        $filesystem = $isPublic ? $this->publicUploadsFilesystem : $this->privateUploadsFilesystem;
        $stream = fopen($uploadedFile->getPathname(), 'r');
        $filesystem->writeStream(
            $path . '/' . $newFilename,
            $stream
        );

        if (is_resource($stream)) {
            fclose($stream);
        }
    }

    private function downloadS3(UploadedFile $uploadedFile, string $newFilename, string $path): void
    {
        $filesystem = $this->s3UploadsFilesystem;
        $stream = fopen($uploadedFile->getPathname(), 'r');
        $filesystem->writeStream(
            $path . '/' . $newFilename,
            $stream
        );

        if (is_resource($stream)) {
            fclose($stream);
        }
    }

    /**
     * @throws FilesystemException
     */
    private function deleteExistingFile(?string $existingFilename, string $path): void
    {
        if ($existingFilename) {
            $this->publicUploadsFilesystem->delete($path .'/'. $existingFilename);
        }
    }
}