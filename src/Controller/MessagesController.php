<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use App\Repository\MessagesRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;


class MessagesController extends AbstractController
{
    /**
     * @Route("/admin/message/index", name="messages_index", methods={"GET"})
     */
    public function index(MessagesRepository $messagesRepository): Response
    {
        return $this->render('admin/messages/index.html.twig', [
            'messages' => $messagesRepository->findAll(),
        ]);
    }

    /**
     * @Route("message/new", name="messages_new", methods={"GET","POST"})
     */
    public function new(Request $request, MailerInterface $mailer): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);
        $errors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $mail = $form->getData();
            $email = (new TemplatedEmail())
                ->from(new Address('***@gmail.com', 'Concept Rénovation'))
                ->to('***@gmail.com')
                ->subject('Contact')
                ->htmlTemplate('mail.html.twig')
                ->context([
                    'mail' => $mail
                ]);
            $message->setType('contact');
            if (isset($_POST['check'])) {
                $mailer->send($email);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($message);

                $entityManager->flush();
                $this->addFlash('success', 'Votre message à bien était envoyé.');
                return $this->redirectToRoute('messages_new');
            } else {
                $this->addFlash('check', 'Veuillez accepter les termes de la politique de confidentialités.');
            }
        }


        return $this->render('public/divers/contact.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    // /**
    //  * @Route("/{id}", name="messages_show", methods={"GET"})
    //  */
    // public function show(Messages $message): Response
    // {
    //     return $this->render('messages/show.html.twig', [
    //         'message' => $message,
    //     ]);
    // }

    /**
     * @Route("/{id}/edit", name="messages_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Messages $message): Response
    {
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('messages_index');
        }

        return $this->render('messages/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/message/delete/{id}", name="messages_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Messages $message): Response
    {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('messages_index');
    }
}
