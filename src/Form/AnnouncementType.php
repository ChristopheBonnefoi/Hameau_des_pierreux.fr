<?php

namespace App\Form;

use App\Entity\Announcement;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un titre',
                    ]),

                    new Length([
                        'min' => 2,
                        'max' => 150,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le titre doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'rows' => 10,
                    'placeholder' => 'Laissez votre commentaire...'
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
            'data_class' => Announcement::class,
        ]);
    }
}
