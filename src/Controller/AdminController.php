<?php

namespace App\Controller;

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
        return $this->render('admin/works/index.html.twig', [
            // 'works' => $worksRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="works_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        // $work = new Works();
        // $form = $this->createForm(WorksType::class, $work);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->persist($work);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('works_index');
        // }

        return $this->render('admin/works/new.html.twig', [
            // 'work' => $work,
            // 'form' => $form->createView(),
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

            return $this->redirectToRoute('users_index');
        }

        return $this->render('admin/users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
