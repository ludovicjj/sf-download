<?php

namespace App\Form\Type;

use App\Entity\Main\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Post $post */
        $post = $options['data'] ?? null;
        $isEdit = $post && $post->getId();

        $builder->add('title', TextType::class);

        $imageFileConstraints= [
            new Image([
                'maxSize' => '5M'
            ])
        ];

        if (!$isEdit || !$post->getImageFilename()) {
            $imageFileConstraints[] = new NotNull([
                'message' => 'Please upload an image',
            ]);
        }

        $builder
            ->add('imageFile', FileType::class, [
                'required'      => false,
                'mapped'        => false,
                'constraints'   => $imageFileConstraints
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class
        ]);
    }
}