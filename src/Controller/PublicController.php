<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Works;
use App\Repository\CategoriesRepository;
use App\Repository\ImagesRepository;
use App\Repository\WorksRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    public function categoriesShow(Categories $category, ImagesRepository $images, PaginatorInterface $paginator, Request $request): Response
    {

        $works = $category->getWorks();

        $realisations = $paginator->paginate(
            $works, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            1 // Nombre de résultats par page
        );

        $realisations->setCustomParameters([
            'align'      => 'center', # center|right (for template: twitter_bootstrap_v4_pagination)
            'size'       => '', # small|large (for template: twitter_bootstrap_v4_pagination)
            'style'      => 'bottom',
            'span_class' => 'whatever',
        ]);

        return $this->render('public/works/show.html.twig', [
            'category' => $category,
            // 'work' => $works,
            'images' => $images->findAll(),
            'realisations' => $realisations
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
