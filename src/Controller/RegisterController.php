<?php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/reg", name="reg")
     */
    public function reg(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $regform = $this->createFormBuilder()
        ->add('username', TextType::class, ['label' => 'Employee'])
        ->add('password', RepeatedType::class, ['type' => PasswordType::class, 'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Password Repeat']])
        ->add('register', SubmitType::class)
        ->getForm();

        $regform->handleRequest($request);

        if ($regform->isSubmitted()) {
            $input = $regform->getData();
            // var_dump($input);

            $user = new User();
            $user->setUsername($input['username']);
            
            $user->setPassword($passwordHasher->hashPassword($user, $input['password']));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('home'));
        }
        
        return $this->render('register/index.html.twig', [
            'regform' => $regform->createView()
        ]);
    }
}






