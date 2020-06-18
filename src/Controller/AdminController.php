<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Users;
use App\Entity\Works;
use App\Form\UsersType;
use App\Form\WorksType;
use App\Repository\WorksRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $work = new Works();
        $form = $this->createForm(WorksType::class, $work);
        $form->handleRequest($request);
        $error = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $work->setUsers($user);


            // On récupère les images transmises
            $images = $form->get('images')->getData();
            // On boucle sur les images
            foreach ($images as $image) {
                if ($image) {
                    $mimeType = $image->getMimeType();
                    if ($mimeType !== 'image/jpeg' && $mimeType !==  'image/png' && $mimeType !== 'image/tiff' && $mimeType !==  'image/webp' && $mimeType !== 'image/jpg') {
                        {
                            $this->addFlash('alerte', 'Veuillez choisir des images valides.(JPEG, JPG, PNG)');
                            return $this->redirectToRoute('works_new');
                        }
                    }
                }
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
            $this->addFlash('success', 'Votre bien à bien était ajouté.');

            return $this->redirectToRoute('works_index');
        }


        return $this->render('admin/works/new.html.twig', [
            'work' => $work,
            'form' => $form->createView(),
            'errors' => $error
        ]);
    }

    /**
     * @Route("/{id}/edit", name="works_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
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
            'id' => $work->getId(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/realisation/{id}", name="works_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function showAdmin(Works $work): Response
    {
        return $this->render('admin/works/show.html.twig', [
            'id' => $work->getId(),
            'work' => $work
        ]);
    }



    // ---------------------- CRUD USERS ----------------------------------



    /**
     * @Route("/new_user", name="users_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
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
}
