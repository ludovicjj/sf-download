<?php

namespace App\Controller\Admin;

use App\Entity\Main\Article;
use App\Entity\Main\ArticleReference;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;

class ArticleReferenceController extends AbstractController
{
    #[Route('/admin/article/{id}/reference', name: 'admin_article_add_reference', methods: ['POST'])]
    public function uploadArticleReference(
        Article $article,
        Request $request,
        UploaderHelper $uploaderHelper,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('reference');

        $violations = $validator->validate(
            $uploadedFile,
            [
                new NotBlank(),
                new File([
                    'mimeTypes' => [
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ]
                ])
            ]
        );

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            $violation = $violations[0];
            $this->addFlash('error', $violation->getMessage());

            return $this->redirectToRoute('admin_article_edit', [
                'id' => $article->getId(),
            ]);
        }

        $filename = $uploaderHelper->uploadArticleReference($uploadedFile);
        $articleReference = new ArticleReference();

        $articleReference
            ->setArticle($article)
            ->setFilename($filename)
            ->setOriginalFilename($uploadedFile->getClientOriginalName())
            ->setMimeType($uploadedFile->getMimeType() ?? 'application/octet-stream')
        ;

        $entityManager->persist($articleReference);
        $entityManager->flush();

        return $this->redirectToRoute('admin_article_edit', [
            'id' => $article->getId()
        ]);
    }

    #[Route('/admin/article/reference/{id}/download', name: 'admin_article_download_reference', methods: ['GET'])]
    public function downloadArticleReference(
        ArticleReference $articleReference,
        UploaderHelper $uploaderHelper
    ): Response {
        $response = new StreamedResponse(function () use ($articleReference, $uploaderHelper) {
            $outputStream = fopen('php://output', 'wb');
            $fileStream = $uploaderHelper->readStream($articleReference->getFilePath(), false);
            stream_copy_to_stream($fileStream, $outputStream);
        });

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $articleReference->getOriginalFilename()
        );

        $response->headers->set('Content-Type', $articleReference->getMimeType());
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    #[Route('/admin/article/reference/{id}', name: 'admin_article_delete_reference', methods: ['DELETE'])]
    public function deleteArticleReference(
        ArticleReference $articleReference,
        UploaderHelper $uploaderHelper,
        EntityManagerInterface $entityManager
    ): Response {
        $entityManager->remove($articleReference);
        $entityManager->flush();

        $uploaderHelper->deleteFile($articleReference->getFilePath(), false);
        return new Response(null, 204);
    }
}