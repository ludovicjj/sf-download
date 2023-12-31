<?php

namespace App\Form\Type;

use App\Entity\Main\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Article $article */
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();

        $builder->add('title', TextType::class);

        $excelFileConstraints  = [
            new File([
                'maxSize' => '5M',
                'extensions' => [
                    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ],
                'extensionsMessage' => "Extension invalide, extension autorisée xlsx"
            ])
        ];

        $imageFileConstraints= [
            new Image([
                'maxSize' => '5M'
            ])
        ];

        if (!$isEdit || !$article->getImageFilename()) {
            $imageFileConstraints[] = new NotNull([
                'message' => 'Please upload an image',
            ]);
        }

        if (!$isEdit || !$article->getExcelFilename()) {
            $excelFileConstraints[] = new NotNull([
                'message' => 'Please upload an excel',
            ]);
        }

        $builder
            ->add('imageFile', FileType::class, [
                'required'      => false,
                'mapped'        => false,
                'constraints'   => $imageFileConstraints
            ])
            ->add('excelFile', FileType::class, [
                'required'      => false,
                'mapped'        => false,
                'constraints'   => $excelFileConstraints
            ])
        ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'data_class' => Article::class
        ]);
    }
}