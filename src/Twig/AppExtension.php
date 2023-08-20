<?php

namespace App\Twig;

use Symfony\Component\Asset\Context\RequestStackContext;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private readonly RequestStackContext $requestStackContext
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
        return $this->requestStackContext->getBasePath() . '/';
    }
}