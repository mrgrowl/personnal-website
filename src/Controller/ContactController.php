<?php

namespace App\Controller;

use App\Form\ContactType;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
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
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            //Config smtp
            $transport = (new Swift_SmtpTransport('mail.mrgrowl.com', 26))
                ->setUsername('contact@mrgrowl.com')
                ->setPassword("Mekki2001/");


            $mailer = new Swift_Mailer($transport);

            $message = (new Swift_Message('Message via mrgrowl.fr'))
                ->setFrom($contact['email'])
                ->setTo('contact@mrgrowl.com')
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig',
                        ['contact' => $contact]
                    ),
                    'text/html'
                );


            $mailer->send($message);

            $this->addFlash('success', 'Votre message à bien été transmis !');
            return $this->redirectToRoute('member_contact');
        }

        return $this->render('user/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
