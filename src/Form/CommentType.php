<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use FOS\CKEditorBundle\Form\Type\CKEditorType;


class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [

                    new NotBlank([
                        'message' => 'Merci de renseigner un contenu',
                    ]),

                    new Length([
                        'min' => 2,
                        'max' => 50000,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le titre doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])

            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'class' => 'd-none',
                ],
                'purify_html' => true,
                'constraints' => [

                    new NotBlank([
                        'message' => 'Merci de renseigner un contenu',
                    ]),

                    new Length([
                        'min' => 2,
                        'max' => 50000,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le titre doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
    
}
