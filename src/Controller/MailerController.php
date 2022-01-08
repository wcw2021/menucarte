<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @Route("/mail", name="mail")
     */
    public function sendEmail(MailerInterface $mailer, Request $request): Response
    {
        // *** first: simple mail text ***
        // $email = (new Email())
        //         ->from ('table1@menucarte.com')
        //         ->to('waiter@menucarte.com')
        //         ->subject('order')
        //         ->text('extra Fries');

        // $mailer->send($email);
        // return new Response('email sent');
 

        // *** second: static html mail ***
        // $table = 'table1';
        // $text = "Please bring more salt and napkins";
        // $email = (new TemplatedEmail())
        //     ->from('table1@menucarte.com')
        //     ->to('waiter@menucarte.com')
        //     ->subject('Order')
        //     ->htmlTemplate('mailer/mail.html.twig')
        //     ->context(['table' => $table, 'text' => $text]);

        // $mailer->send($email);
        // return new Response('email sent'); 


        $emailForm = $this->createFormBuilder()
            ->add('message', TextareaType::class,
                ['attr' => array('rows' => '5')])
            ->add('send', SubmitType::class, [ 'attr' => ['class' => 'btn btn-outline-danger float-right'] ])
            ->getForm();

        $emailForm->handleRequest($request);

        if($emailForm->isSubmitted()){
            $input = $emailForm->getData();
            $text = ($input['message']);

            $table = 'table1';
            $email = (new TemplatedEmail())
                ->from('table1@menucarte.com')
                ->to('waiter@menucarte.com')
                ->subject('Message')
                ->htmlTemplate('mailer/mail.html.twig')
                ->context(['table' => $table, 'text' => $text]);

            $mailer->send($email);

            $this->addFlash('message', 'Message has been sent');
            return $this->redirect($this->generateUrl('mail'));
        }

        return $this->render('mailer/index.html.twig', ['emailForm' => $emailForm->createView()]);
    }
}






