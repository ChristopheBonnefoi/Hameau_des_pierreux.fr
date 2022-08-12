<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnnouncementRepository;

#[Route('',name: 'main_')]
class MainController extends AbstractController
{
    /**
     * contrôleur de la page d'accueil
     */
    #[Route('/', name: 'home')]
    public function home(AnnouncementRepository $announcementRepository): Response
    {
        return $this->render('main/home.html.twig',[
            'announcements' => $announcementRepository->findAll(),
        ] );
    }

    /**
     * contrôleur de la page d'itinéraire
     */
    #[Route('itineraire', name: 'itineraire')]
    public function itineraire(): Response
    {
        return $this->render('main/itineraire.html.twig');
    }

    /**
     * contrôleur de la page Activité sportive
     */
    #[Route('sport', name: 'sport')]
    public function sport(): Response
    {
        return $this->render('main/sport.html.twig');
    }

    /**
     * contrôleur de la page histoire
     */
    #[Route('histoire', name: 'histoire')]
    public function histoire(): Response
    {
        return $this->render('main/histoire.html.twig');

    }

    /**
     * contrôleur de la page Activité culturel
     */
    #[Route('culture', name: 'culture')]
    public function culture(): Response
    {
        return $this->render('main/culture.html.twig');

    }

    /**
     * contrôleur de la page partenaire
     */
    #[Route('partenaire', name: 'partner')]
    public function partner(): Response
    {
        return $this->render('main/partner.html.twig');

    }

    /**
     * contrôleur de la page Admin
     */
    #[Route('admin', name: 'admin')]
    public function admin(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException();
        }else {
            return $this->render('main/admin.html.twig');
        }
    }

    /**
     * contrôleur de la page CGU
     */
    #[Route('cgu', name: 'cgu')]
    public function cgu(): Response
    {
        return $this->render('main/cgu.html.twig');

    }

    /**
     * Contrôleur de la page Politique de confidentialité
     */
    #[Route('privacy', name: 'privacy')]
    public function privacy(): Response
    {
        return $this->render('main/privacy.html.twig');

    }
}
