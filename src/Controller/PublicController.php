<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Works;
use App\Repository\CategoriesRepository;
use App\Repository\ImagesRepository;
use App\Repository\WorksRepository;
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
     * @Route("/politique_confidentialites", name="politique", methods={"GET"})
     */
    public function politique(): Response
    {
        return $this->render('public/divers/confidentialites.html.twig');
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
     * @Route("/categories/{id}", name="categories_show", methods={"GET"})
     */
    public function categoriesShow(Categories $category, ImagesRepository $images): Response
    {

        $works = $category->getWorks();

        return $this->render('public/works/show.html.twig', [
            'category' => $category,
            'work' => $works,
            'images' => $images->findAll()
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
