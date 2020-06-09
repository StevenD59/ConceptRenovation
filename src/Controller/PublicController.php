<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Works;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{

    /**
     * @Route("/accueil", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('public/divers/accueil.html.twig');
    }

    /**
     * @Route("/profil", name="profil", methods={"GET"})
     */
    public function profil(): Response
    {
        return $this->render('public/divers/profil.html.twig');
    }

    /**
     * @Route("/collaborateurs", name="collaborateurs", methods={"GET"})
     */
    public function collaborateurs(): Response
    {
        return $this->render('public/divers/collaborateurs.html.twig');
    }

    /**
     * @Route("/contact", name="contact", methods={"GET"})
     */
    public function contact(): Response
    {
        return $this->render('public/divers/contact.html.twig');
    }
    
    /**
     * @Route("/categories", name="categories", methods={"GET"})
     */
    public function categories(CategoriesRepository $categoriesRepository): Response
    {

        $categories = $categoriesRepository->findAll();

        return $this->render('public/divers/categories.html.twig', [
            'categories' => $categories
        ]);
    }

    
    /**
     * @Route("/devis", name="devis", methods={"GET"})
     */
    public function devis(): Response
    {
        return $this->render('public/divers/devis.html.twig');
    }

    /**
     * @Route("/realisations/{id}", name="works_public_show", methods={"GET"})
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
