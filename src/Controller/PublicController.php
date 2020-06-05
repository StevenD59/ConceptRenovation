<?php

namespace App\Controller;

use App\Entity\Works;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{




    /**
     * @Route("/public/accueil", name="accueil", methods={"GET"})
     */
    public function accueil(): Response
    {
        return $this->render('public/divers/accueil.html.twig');
    }

    /**
     * @Route("/public/profil", name="profil", methods={"GET"})
     */
    public function profil(): Response
    {
        return $this->render('public/divers/profil.html.twig');
    }

    /**
     * @Route("/public/collaborateurs", name="collaborateurs", methods={"GET"})
     */
    public function collaborateurs(): Response
    {
        return $this->render('public/divers/collaborateurs.html.twig');
    }

    /**
     * @Route("/public/contact", name="contact", methods={"GET"})
     */
    public function contact(): Response
    {
        return $this->render('public/divers/contact.html.twig');
    }

    /**
     * @Route("/public/devis", name="devis", methods={"GET"})
     */
    public function devis(): Response
    {
        return $this->render('public/divers/devis.html.twig');
    }

    /**
     * @Route("/public/{id}", name="works_public_show", methods={"GET"})
     */
    public function show(Works $work): Response
    {
        return $this->render('public/works/show.html.twig', [
            'work' => $work,
        ]);
    }



    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('works_index');
        }
        return $this->render('admin/users/login.html.twig');
    }
    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
    }
}
