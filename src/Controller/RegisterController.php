<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository; 
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegisterType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/register/add', name: 'app_register_add')]
    public function addUser(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        $message = '';
        $user = new User();
        $type = 'success';
        $msg = 'Le compte a été ajouté en BDD!';
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() AND $form->isValid()) {
            $password = $user->getPassword();
            $hash = $hasher->hashPassword($user, $password);
            $user->setPassword($hash);
            // Version en une ligne
            // $user->setPassword($hasher->hashPassword($user, $user->getpassword()));
            $user ->setRoles(['ROLE_USER','ROLE_ADMIN']);
            $em->persist($user);
            $em->flush();
        }
        else{
            $type = 'danger';
            $msg = 'Informations incorrectes.';
        }
        $this->addFlash($type, $message);
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
