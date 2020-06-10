<?php

namespace App\Controller;

use App\Entity\Quotes;
use App\Form\QuotesType;
use App\Repository\QuotesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class QuotesController extends AbstractController
{
    /**
     * @Route("admin/devis/index", name="quotes_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(QuotesRepository $quotesRepository): Response
    {
        return $this->render('admin/quotes/index.html.twig', [
            'quotes' => $quotesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/devis/new", name="devis", methods={"GET","POST"})
     */
    public function newQuotes(Request $request): Response
    {
        $quote = new Quotes();
        $form = $this->createForm(QuotesType::class, $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre email à bien était envoyer et on vous répondra dans les meilleurs délais!');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quote);
            $entityManager->flush();

            return $this->redirectToRoute('devis');
        }

        return $this->render('public/divers/devis.html.twig', [
            'quote' => $quote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/devis/{id}", name="quotes_show", methods={"GET"})
     */
    public function show(Quotes $quote): Response
    {
        return $this->render('admin/quotes/show.html.twig', [
            'quote' => $quote,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quotes_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Quotes $quote): Response
    {
        $form = $this->createForm(QuotesType::class, $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quotes_index');
        }

        return $this->render('quotes/edit.html.twig', [
            'quote' => $quote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quotes_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Quotes $quote): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quote->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quotes_index');
    }
}
