<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/admin/upload/test', name: 'admin_upload_test')]
    public function temporaryUploadAction(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->files->get('file');
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

            $uploadedFile->move($destination);
        }

        return $this->render('admin/upload_test.html.twig');
    }
}