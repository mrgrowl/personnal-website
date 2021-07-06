<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="member_contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $message = (new \Swift_Message('Message via mrgrowl.fr'))
                ->setFrom($contact['email'])
                ->setTo('mrgrowl@contact.fr')
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig',
                        ['contact' => $contact]
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('success', 'Le mail a bien été envoyé !');
            return $this->redirectToRoute('member_contact');
        }

        return $this->render('user/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
