<?php

namespace App\Form;

use App\Entity\Prices;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PricesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cottage', CKEditorType::class, [
                'label' => 'Cabanon',
                'attr' => [
                    'class' => 'd-none',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de renseigner un contenue pour l'espace"
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 5000,
                        'minMessage' => "Votre contenue doit contenir au moins {{ limit }} caractères",
                        'maxMessage' => "Votre contenue doit contenir au maximum {{ limit }} caractères",
                    ]),
                ],
                'purify_html' => true, // Sécurise l'envoie à la BDD
            ])
            ->add('lodge', CKEditorType::class, [
                'label' => 'Chalet',
                'attr' => [
                    'class' => 'd-none',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de renseigner un contenue pour l'espace"
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 5000,
                        'minMessage' => "Votre contenue doit contenir au moins {{ limit }} caractères",
                        'maxMessage' => "Votre contenue doit contenir au maximum {{ limit }} caractères",
                    ]),
                ],
                'purify_html' => true, // Sécurise l'envoie à la BDD
            ])
            ->add('tent', CKEditorType::class, [
                'label' => 'Tente',
                'attr' => [
                    'class' => 'd-none',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de renseigner un contenue pour l'espace"
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 5000,
                        'minMessage' => "Votre contenue doit contenir au moins {{ limit }} caractères",
                        'maxMessage' => "Votre contenue doit contenir au maximum {{ limit }} caractères",
                    ]),
                ],
                'purify_html' => true, // Sécurise l'envoie à la BDD
            ])
            ->add('breakfast', CKEditorType::class, [
                'label' => 'Petit Déjeuner',
                'attr' => [
                    'class' => 'd-none',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de renseigner un contenue pour l'espace"
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 5000,
                        'minMessage' => "Votre contenue doit contenir au moins {{ limit }} caractères",
                        'maxMessage' => "Votre contenue doit contenir au maximum {{ limit }} caractères",
                    ]),
                ],
                'purify_html' => true, // Sécurise l'envoie à la BDD
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prices::class,
        ]);
    }
}
