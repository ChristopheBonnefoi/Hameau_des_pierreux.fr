<?php

namespace App\Form;

use App\Entity\Habitat;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class HabitatType extends AbstractType
{
    //Format d'image accepter pour le champ pictureProfile
    private $allowedMimeTypes = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champs Title
            ->add('title', TextType::class, [
                'label' => 'Nom*',
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de renseigner un nom pour l'espace"
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => "Votre nom du nouvel espace doit contenir au moins {{ limit }} caractères",
                        'maxMessage' => "Votre nom du nouvel espace doit contenir au maximum {{ limit }} caractères",
                    ]),
                ]
            ])

            // Champs Description
            ->add('description', TextType::class, [
                'label' => 'Slogan*',
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de renseigner une description pour l'espace"
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 200,
                        'minMessage' => "Votre description doit contenir au moins {{ limit }} caractères",
                        'maxMessage' => "Votre description doit contenir au maximum {{ limit }} caractères",
                    ]),
                ]
            ])

            //Champs nombre de places
            ->add('places', TextType::class, [
                'label' => 'Nombre de personnes*',
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de renseigner le nombre de personnes pour l'espace"
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 150,
                        'minMessage' => "Le nombre de personnes doit contenir au moins {{ limit }} caractères",
                        'maxMessage' => "Le nombre de personnes doit contenir au maximum {{ limit }} caractères",
                    ]),
                ]
            ])

            // Champs pour décrire de la chambre
            ->add('content', CKEditorType::class, [
                'label' => 'Description*',
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

            // Champs image de couverture
            ->add('coverImage', FileType::class, [
                'label' => 'Sélectionnez une photo de couverture*',
                'data_class' => null,
                'attr' => [
                    'accept' => implode(', ', $this->allowedMimeTypes),
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de choisir une image"
                    ]),

                    new File([
                        'maxSize' => "10M",
                        'maxSizeMessage' => "Fichier trop volumineux ({{ size }} {{ suffix }}). La taille maximum est de {{ limit }} {{ suffix }}",
                        'mimeTypes' => $this->allowedMimeTypes,
                        'mimeTypesMessage' => "Ce type de fichier est pas autoriser ({{ type }}). Les types autorisés sont {{ types }}",
                    ]),
                ],
            ])

            // Champs images
            // Il n'est pas lié à la base de données (mapped à false)
            ->add('images', FileType::class, [
                'label' => "Sélection d'images* (choix multiple)",
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => implode(', ', $this->allowedMimeTypes),
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de choisir une ou plusieurs images"
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Habitat::class,
        ]);
    }
}
