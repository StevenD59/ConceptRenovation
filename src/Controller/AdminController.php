<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Users;
use App\Entity\Works;
use App\Form\UsersType;
use App\Form\WorksType;
use App\Repository\WorksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/admin")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/liste", name="works_index", methods={"GET"})
     */
    public function index(WorksRepository $worksRepository): Response
    {

        $work = $worksRepository->findAll();

        return $this->render('admin/works/index.html.twig', [
            'work' => $work,
        ]);
    }


    /**
     * @Route("/new", name="works_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $work = new Works();
        $form = $this->createForm(WorksType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $work->setUsers($user);

            // On récupère les images transmises
            $images = $form->get('images')->getData();
            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('upload_directory'),
                    $fichier
                );
                // On crée l'image dans la base de données
                $img = new Images();
                $img->setName($fichier);
                $work->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($work);
            $entityManager->flush();

            return $this->redirectToRoute('works_index');
        }


        return $this->render('admin/works/new.html.twig', [
            'work' => $work,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="works_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Works $work): Response
    {
        $form = $this->createForm(WorksType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('works_index');
        }

        return $this->render('admin/works/edit.html.twig', [
            'work' => $work,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="works_show", methods={"GET"})
     */
    public function showAdmin(Works $work): Response
    {
        return $this->render('public/works/show.html.twig', [
            'id' => $work->getId(),
            'work' => $work
            
        ]);
    }



    // ---------------------- CRUD USERS ----------------------------------



    /**
     * @Route("/new_user", name="users_new", methods={"GET","POST"})
     */
    public function newUsers(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users_new');
        }

        return $this->render('admin/users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }




    // ---------------------------- CONNEXION ---------------------------------------------

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
