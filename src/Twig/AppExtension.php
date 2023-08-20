<?php

namespace App\Twig;

use App\Service\UploaderHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private readonly UploaderHelper $uploaderHelper
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('uploaded_article_asset', [$this, 'getUploadedAssetPath'])
        ];
    }

    public function getUploadedAssetPath(string $path): string
    {
        return $this->uploaderHelper->getImagePath($path);
    }
}