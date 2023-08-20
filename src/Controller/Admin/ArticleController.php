<?php

namespace App\Controller\Admin;

use App\Entity\Main\Article;
use App\Form\Type\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/admin/articles', name: 'admin_articles')]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('admin/article/index.html.twig', [
            'articles' => $articleRepository->findAll()
        ]);
    }

    #[Route('/admin/article/new', name: 'admin_article_new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        UploaderHelper $uploaderHelper,
    ): Response {
        $articleForm = $this->createForm(ArticleType::class)->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            /** @var Article $article */
            $article = $articleForm->getData();

            /** @var UploadedFile $uploadedFile */
            $imageFile = $articleForm->get('imageFile')->getData();

            /** @var UploadedFile $excelFile */
            $excelFile = $articleForm->get('excelFile')->getData();

            $imageFilename = $uploaderHelper->uploadArticleImageFile($imageFile, null);
            $article->setImageFilename($imageFilename);

            $excelFilename = $uploaderHelper->uploadArticleExcelFile($excelFile, null);
            $article->setExcelFilename($excelFilename);

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin_articles');
        }

        return $this->render('admin/article/new.html.twig', [
            'articleForm' => $articleForm
        ]);
    }

    #[Route('/admin/article/{id}/edit', name: 'admin_article_edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        UploaderHelper $uploaderHelper,
        Article $article
    ): Response {
        $articleForm = $this->createForm(ArticleType::class, $article)->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            /** @var Article $article */
            $article = $articleForm->getData();

            /** @var UploadedFile|null $uploadedFile */
            $imageFile = $articleForm->get('imageFile')->getData();

            /** @var UploadedFile|null $excelFile */
            $excelFile = $articleForm->get('excelFile')->getData();

            if ($imageFile) {
                $imageFilename = $uploaderHelper->uploadArticleImageFile($imageFile, $article->getImageFilename());
                $article->setImageFilename($imageFilename);
            }

            if ($excelFile) {
                $excelFilename = $uploaderHelper->uploadArticleExcelFile($excelFile, $article->getExcelFilename());
                $article->setExcelFilename($excelFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('admin_articles');
        }

        return $this->render('admin/article/edit.html.twig', [
            'articleForm'   => $articleForm,
            'article'       => $article
        ]);
    }
}