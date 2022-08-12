<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Form\AnnouncementType;
use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/announcement')]
class AnnouncementController extends AbstractController
{

// Route de page d'accueil des annnonces (à décommenter si besoin)

//    #[Route('/', name: 'app_announcement_index', methods: ['GET'])]
//    public function index(AnnouncementRepository $announcementRepository): Response
//    {
//        return $this->render('announcement/index.html.twig', [
//            'announcements' => $announcementRepository->findAll(),
//        ]);
//    }

// Route de création du formulaire d'annonce en page d'accueil
    #[Route('/new', name: 'app_announcement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnnouncementRepository $announcementRepository): Response
    {
        // Vérification du role Admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            // sinon erreur 403, accès interdit
            throw new AccessDeniedHttpException();
        }else {
            // Création du formulaire
            $announcement = new Announcement();
            $form = $this->createForm(AnnouncementType::class, $announcement);
            $form->handleRequest($request);

            //Si le formulaire est envoyé et sans erreurs
            if ($form->isSubmitted() && $form->isValid()) {
                $announcementRepository->add($announcement, true);

                // Message flash de succès
                $this->addFlash('success', 'votre annonce a bien été envoyer');

                return $this->redirectToRoute('main_home', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('announcement/new.html.twig', [
                'announcement' => $announcement,
                'form' => $form,
            ]);
        }
    }

//    Route de détails d'une annonce (à décommenter si besoin)

//    #[Route('/{id}', name: 'app_announcement_show', methods: ['GET'])]
//    #[IsGranted('ROLE_ADMIN')]
//    public function show(Announcement $announcement): Response
//    {
//        return $this->render('announcement/show.html.twig', [
//            'announcement' => $announcement,
//        ]);
//    }

// Route de modification d'une annonce
    #[Route('/{id}/edit', name: 'app_announcement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Announcement $announcement, AnnouncementRepository $announcementRepository): Response
    {
        // Vérification du role Admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            // sinon erreur 403, accès interdit
            throw new AccessDeniedHttpException();
        }else {
            $form = $this->createForm(AnnouncementType::class, $announcement);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $announcementRepository->add($announcement, true);

                // Message flash de succès
                $this->addFlash('success', 'Votre annonce a bien été modifier');

                return $this->redirectToRoute('main_home', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('announcement/edit.html.twig', [
                'announcement' => $announcement,
                'form' => $form,
            ]);
        }
    }

// Route de suppression d'une annonce en page d'accueil
    #[Route('/{id}', name: 'app_announcement_delete', methods: ['POST'])]
    public function delete(Request $request, Announcement $announcement, AnnouncementRepository $announcementRepository): Response
    {
        // Vérification du role Admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            // sinon erreur 403, accès interdit
            throw new AccessDeniedHttpException();
        } else {
            if ($this->isCsrfTokenValid('delete' . $announcement->getId(), $request->request->get('_token'))) {


                $announcementRepository->remove($announcement, true);

                // Message flash de succès
                $this->addFlash('success', 'Votre annonce a bien été supprimer');
            }
            return $this->redirectToRoute('main_home', [], Response::HTTP_SEE_OTHER);
        }
    }
}


