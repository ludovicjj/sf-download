<?php

namespace App\Controller\Admin;

use App\Entity\Main\Post;
use App\Form\Type\PostType;
use App\Repository\PostRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/admin/posts', name: 'admin_post')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('admin/post/index.html.twig', [
            'posts' => $postRepository->findAll()
        ]);
    }

    #[Route('/admin/post/new', name: 'admin_post_new')]
    public function new(Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $entityManager): Response
    {
        $postForm = $this->createForm(PostType::class)->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            /** @var Post $post */
            $post = $postForm->getData();

            /** @var UploadedFile $uploadedFile */
            $imageFile = $postForm->get('imageFile')->getData();

            $imageFilename = $uploaderHelper->uploadS3($imageFile);

            $post
                ->setImageFilename($imageFilename)
                ->setOriginalFilename($imageFile->getClientOriginalName())
                ->setMimeType($imageFile->getMimeType() ?? 'application/octet-stream');

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('admin_post');
        }

        return $this->render('admin/post/new.html.twig', [
            'postForm' => $postForm
        ]);
    }

    #[Route('/admin/post/{id}/edit', name: 'admin_post_edit')]
    public function edit()
    {

    }
}