<?php

namespace App\Controller;

use App\Entity\Prices;
use App\Form\PricesType;
use App\Repository\PricesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/prices')]
class PricesController extends AbstractController
{
    #[Route('/', name: 'app_prices_index', methods: ['GET'])]
    public function index(PricesRepository $pricesRepository): Response
    {
        return $this->render('prices/index.html.twig', [
            'prices' => $pricesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_prices_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PricesRepository $pricesRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException();
        } else {
            $price = new Prices();
            $form = $this->createForm(PricesType::class, $price);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $pricesRepository->add($price, true);

                return $this->redirectToRoute('app_prices_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('prices/new.html.twig', [
                'price' => $price,
                'form' => $form,
            ]);
        }
    }

//    #[Route('/{id}', name: 'app_prices_show', methods: ['GET'])]
//    public function show(Prices $price): Response
//    {
//        return $this->render('prices/show.html.twig', [
//            'price' => $price,
//        ]);
//    }

    #[Route('/{id}/edit', name: 'app_prices_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prices $price, PricesRepository $pricesRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException();
        } else {
            $form = $this->createForm(PricesType::class, $price);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $pricesRepository->add($price, true);

                return $this->redirectToRoute('app_prices_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('prices/edit.html.twig', [
                'price' => $price,
                'form' => $form,
            ]);
        }
    }

//    #[Route('/{id}', name: 'app_prices_delete', methods: ['POST'])]
//    public function delete(Request $request, Prices $price, PricesRepository $pricesRepository): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$price->getId(), $request->request->get('_token'))) {
//            $pricesRepository->remove($price, true);
//        }
//
//        return $this->redirectToRoute('app_prices_index', [], Response::HTTP_SEE_OTHER);
//    }
}
