<?php

namespace App\Controller;

use App\Entity\Works;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    /**
     * @Route("/public/{id}", name="works_show", methods={"GET"})
     */
    public function show(Works $work): Response
    {
        return $this->render('public/works/test.html.twig', [
            'work' => $work,
        ]);
    }
}
