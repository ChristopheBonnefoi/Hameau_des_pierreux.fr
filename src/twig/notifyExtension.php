<?php

namespace App\twig;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class notifyExtension extends AbstractExtension
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        // Récupération de Entity Manager
        $this->em= $em;
    }

    public function getFunctions(): array
    {
        // Retourne un tableau qui va déclarer la fonction twig getNotifications
        return [
            new TwigFunction('notify', [$this, 'getNotifications'])
        ];
    }

    public function getNotifications()
    {
        // Retourne les notifications
        return $this->em->getRepository(Comment::class)->findAll();
    }

}