<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'app_notification')]
    public function index(ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
    {
        // Vérification du role Admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            // sinon erreur 403, accès interdit
            throw new AccessDeniedHttpException();
        } else {
            return $this->render('notification/index.html.twig');
        }
    }
}
