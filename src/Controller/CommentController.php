<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Recaptcha\RecaptchaValidator;
use Symfony\Component\Form\FormError;

#[Route('/comment')]
class CommentController extends AbstractController
{

    /**
     * @throws TransportExceptionInterface
     */
    // Route de création et de vérification du formulaire + Recaptcha + système de notification avec boolean + emails
    #[Route('/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommentRepository $commentRepository, ManagerRegistry $doctrine, RecaptchaValidator $recaptcha, MailerInterface $mailer): Response
    {
        // Création d'un nouvel objet de la classe Comment
        $comment = new Comment();

        // Création d'un nouveau formulaire
        $form = $this->createForm(CommentType::class, $comment);

        // Traitement des données du formulaire
        $form->handleRequest($request);

        // Vérification que le formulaire a bien été envoyé
        if($form->isSubmitted()){

            // Récupération de la réponse envoyée par le captcha dans le formulaire
            // ( $_POST['g-recaptcha-response'] )
            $recaptchaResponse = $request->request->get('g-recaptcha-response', null);

            // Si le captcha n'est pas valide, on crée une nouvelle erreur dans le formulaire (ce qui l'empêchera de créer le commentaire et affichera l'erreur)
            // $request->server->get('REMOTE_ADDR') -----> Adresse IP de l'utilisateur dont la méthode verify() a besoin
            if($recaptchaResponse == null || !$recaptcha->verify( $recaptchaResponse, $request->server->get('REMOTE_ADDR') )){

                // Ajout d'une nouvelle erreur manuellement dans le formulaire
                $form->addError(new FormError('Le Captcha doit être validé !'));
            }

        }

        if ($form->isSubmitted() && $form->isValid()) {

            // Hydratation de la date + boolean pour la modération
            $comment
                ->setPublicationDate(new \DateTime())
                ->setIsPublished(false)
                ->setNotification(true);

            $commentRepository->add($comment, true);

            // Création d'un email pour na notification pour l'admin
            $email = (new TemplatedEmail())
                ->from('admin@test.com')
                ->to('user@test.com')
                ->subject('Nouvel notification')
                ->text('Le Hameau des Pierreux Hébergement pour groupe de 6 à 50 personnes Vous avez reçu un nouveau commentaire Veuillez vous connecté à votre page admin de notification')
                ->htmlTemplate('comment/notification.html.twig');
            // Envoie du mail de notification
            $mailer->send($email);


            // Message flash de succès
            $this->addFlash('success', 'Merci pour votre retour. Votre commentaire sera publié après modération.');
            // redirection
            return $this->redirectToRoute('app_comment_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'comment_form' => $form,
        ]);
    }

    //    Route des nouveaux commentaires ajouter par un utilisateur et envoyer en modération avant validation
    #[Route('/', name: 'app_comment_index', methods: ['GET', 'POST'])]
    public function index(CommentRepository $commentRepository, ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator ): Response
    {
        // Vérification du role Admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            // sinon erreur 403, accès interdit
            throw new AccessDeniedHttpException();
        }else {
            // Pagination
            $requestedPage = $request->query->getInt('page', 1);
            // si la page est inférieur à 1 : erreur 404
            if ($requestedPage < 1) {
                throw new NotFoundHttpException();
            }

            $em = $doctrine->getManager();
            // Affichage des commentaires du plus récent au plus ancien
            $query = $em->createQuery('SELECT a FROM App\Entity\Comment a ORDER BY a.publicationDate DESC');

            $comments = $paginator->paginate(
                $query,     // Requête créée juste avant
                $requestedPage,     // Page qu'on souhaite voir
                limit: 15,       // Nombre de commentaires qu'on souhaite afficher par page
            );

            return $this->render('comment/index.html.twig', [
                'comments' => $comments,
            ]);
        }

    }
    //     Route activation d'un commentaire
    #[Route('/activer/{id}', name: 'app_comment_activer')]
    public function activer(Comment $comment, CommentRepository $commentRepository, ManagerRegistry $doctrine): Response
    {
        // Vérification du role Admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            // sinon erreur 403, accès interdit
            throw new AccessDeniedHttpException();
        }else {
            // Récupération du commentaire, s'il est actif -> désactive et s'il est inactif -> active
            $comment->setIsPublished(!$comment->getIsPublished());

            // Récupération du manager des entités de Doctrine
            $em = $doctrine->getManager();
            // Préparation à la sauvegarde
            $em->persist($comment);
            // sauvegarde en base de données
            $em->flush();

            return new response('true');
        }
    }

    //     Route suppression d'une notification
    #[Route('/supprimer/{id}', name: 'app_comment_supprimer')]
    public function supprimer(Comment $comment, CommentRepository $commentRepository, ManagerRegistry $doctrine): Response
    {
        // Vérification du role Admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            // sinon erreur 403, accès interdit
            throw new AccessDeniedHttpException();
        }else {
            // Récupération du commentaire, s'il est actif -> désactive et s'il est inactif -> active
            $comment->setNotification(!$comment->getNotification());

            // Récupération du manager des entités de Doctrine
            $em = $doctrine->getManager();
            // Préparation à la sauvegarde
            $em->persist($comment);
            // sauvegarde en base de données
            $em->flush();

            return new response('true');
        }
    }

    //    Route des commentaires valider après modération
    #[Route('/show', name: 'app_comment_show', methods: ['GET', 'POST'])]
    public function show(ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
    {
        // Récupération de $_GET['page'], 1 si elle n'existe pas
        $requestedPage = $request->query->getInt('page', 1);

        // Pagination
        if($requestedPage < 1){
            // si la page est inférieur à 1 : erreur 404
            throw new NotFoundHttpException();
        }

        // Récupération du manager des entités de Doctrine
        $em = $doctrine -> getManager();

        // Après modération affichage des commentaires du plus récent au plus ancien
        $query = $em->createQuery('SELECT a FROM App\Entity\Comment a WHERE a.isPublished = 1 ORDER BY a.publicationDate DESC');

        $comments = $paginator->paginate(
            $query,     // Requête créée juste avant
            $requestedPage,     // Page qu'on souhaite voir
            limit:15,       // Nombre de commentaires qu'on souhaite afficher par page
        );

        return $this->render('comment/show.html.twig', [
            'comments' => $comments,
        ]);


    }

// Route de modification d'un commentaire (à décommenter si besoin)

//    #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
//    #[IsGranted('ROLE_ADMIN')]
//    public function edit(Request $request, Comment $comment, CommentRepository $commentRepository): Response
//    {
//        $form = $this->createForm(CommentType::class, $comment);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $commentRepository->add($comment, true);
//
//            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('comment/edit.html.twig', [
//            'comment' => $comment,
//            'comment_form' => $form,
//        ]);
//    }

    // Route de suppression d'un commentaire
    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        // Vérification du role Admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            // sinon erreur 403, accès interdit
            throw new AccessDeniedHttpException();
        } else {
            if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
                $commentRepository->remove($comment, true);

                // Message flash de succès
                $this->addFlash('success', 'Le commentaire a bien été supprimé');
            }

            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}