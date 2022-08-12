<?php

namespace App\Controller;

use App\Entity\Habitat;
use App\Entity\HabitatImages;
use App\Form\EditHabitatType;
use App\Form\HabitatType;
use App\Repository\HabitatImagesRepository;
use App\Repository\HabitatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/habitat')]
class HabitatController extends AbstractController
{
    /**
     * contrôleur de la page liste des habitations
     */
    #[Route('/', name: 'app_habitat_index', methods: ['GET'])]
    public function index(HabitatRepository $habitatRepository): Response
    {
        return $this->render('habitat/index.html.twig', [
            'habitats' => $habitatRepository->findAll(),
        ]);
    }

    /**
     * contrôleur de la création d'habitation
     */
    #[Route('/new', name: 'app_habitat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HabitatRepository $habitatRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException();
        } else {
            $habitat = new Habitat();
            $form = $this->createForm(HabitatType::class, $habitat);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                //On recupere les images transmises
                $images = $form->get('images')->getData();

                // On boucle sur les images
                foreach ($images as $image) {
                    // On génére un nouveau nom de fichier
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                    // on copie le fichier dans le dossier uploads
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );

                    // On stocke le nom de l'image dans la base de données
                    $img = new HabitatImages();
                    $img->setName($fichier);
                    $habitat->addHabitatImage($img);

                }

                $habitatRepository->add($habitat, true);

                //Cover Image

                //Recupération de l'image
                $coverImage = $form->get('coverImage')->getData();

                // Hash du nom en le rendant unique en meme temps
                $newFileName = md5(time() . rand() . uniqid()) . '.' . $coverImage->guessExtension();

                // Hydratation à la BDD avec le nouveau nom de l'image
                $habitat
                    ->setCoverImage($newFileName);

                // Déplace l'image dans le fichier uploads ou on stock les fichiers envoyer avec son nouveau nom
                $coverImage->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );

                // On envoie tout à la BDD
                $habitatRepository->add($habitat, true);

                // Message flash de succès
                $this->addFlash('success', 'votre espace a bien été envoyer');


                return $this->redirectToRoute('app_habitat_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('habitat/new.html.twig', [
                'habitat' => $habitat,
                'habitat_form' => $form,
            ]);
        }
    }

    /**
     * contrôleur de la vue complete d'une habitation
     */
    #[Route('/{id}', name: 'app_habitat_show', methods: ['GET'])]
    public function show(Habitat $habitat): Response
    {
        return $this->render('habitat/show.html.twig', [
            'habitat' => $habitat,
        ]);
    }

    /**
     * contrôleur de l'édition d'habitation
     */
    #[Route('/{id}/edit', name: 'app_habitat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Habitat $habitat, HabitatRepository $habitatRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException();
        } else {
            $form = $this->createForm(EditHabitatType::class, $habitat);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                //On recupere les images transmises
                $images = $form->get('images')->getData();

                // On boucle sur les images
                foreach ($images as $image) {
                    // On génére un nouveau nom de fichier
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                    // on copie le fichier dans le dossier uploads
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );

                    // On stocke le nom de l'image dans la base de données
                    $img = new HabitatImages();
                    $img->setName($fichier);
                    $habitat->addHabitatImage($img);

                }


                $habitatRepository->add($habitat, true);

                // Cover Image

                // Recupération de l'image
                $coverImage = $form->get('coverImage')->getData();


                if ($coverImage != null) {
                    // Hash du nom en le rendant unique en meme temps
                    $newFileName = md5(time() . rand() . uniqid()) . '.' . $coverImage->guessExtension();

                    // Hydratation à la BDD avec le nouveau nom de l'image
                    $habitat
                        ->setCoverImage($newFileName);

                    // Déplace l'image dans le fichier uploads ou on stock les fichiers envoyer avec son nouveau nom
                    $coverImage->move(
                        $this->getParameter('images_directory'),
                        $newFileName
                    );
                }
                // On envoie tout à la BDD
                $habitatRepository->add($habitat, true);

                // Message flash de succès
                $this->addFlash('success', 'Votre espace a bien été modifier');

                return $this->redirectToRoute('app_habitat_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('habitat/edit.html.twig', [
                'habitat' => $habitat,
                'edit_habitat_form' => $form,
            ]);
        }
    }

    /**
     * contrôleur de la suppression d'habitation
     */
    #[Route('/{id}', name: 'app_habitat_delete', methods: ['POST'])]
    public function delete(Request $request, Habitat $habitat, HabitatRepository $habitatRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException();
        } else {
            // Suppression de l'habitat
            if ($this->isCsrfTokenValid('delete' . $habitat->getId(), $request->request->get('_token'))) {
                $habitatRepository->remove($habitat, true);
            }

            return $this->redirectToRoute('app_habitat_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/supprime/image/{id}', name: 'habitat_delete_image', methods: ['DELETE'])]
    public function deleteImage(HabitatImages $image, Request $request, HabitatImagesRepository $habitatImagesRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        //On verifies si le token est valide
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            // On récuperer le nom de l'image
            $name = $image->getName();

            //On supprime le fichier
            unlink($this->getParameter('images_directory') . '/' . $name);

            // On supprime de la base de donnée
            $habitatImagesRepository->remove($image, true);

            // Message flash de succès
            $this->addFlash('success', 'Votre espace a bien été supprimer');

            // On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}


