<?php

namespace App\Controller;

use App\Entity\Works;
use App\Form\WorksType;
use App\Repository\WorksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/works")
 */
class WorksController extends AbstractController
{

    

    

    /**
     * @Route("/{id}", name="works_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Works $work): Response
    {
        if ($this->isCsrfTokenValid('delete' . $work->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($work);
            $entityManager->flush();
        }

        return $this->redirectToRoute('works_index');
    }
}
