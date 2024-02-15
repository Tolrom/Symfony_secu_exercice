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
    public function addUser(Request $request, EntityManagerInterface $em): Response
    {
        $message = '';
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() AND $form->isValid()) {
            // $password = $user->getPassword();
            // dd($password);
            // $hash = $hasher->hashPassword($user, $password);
            // $user->setPassword($hash);
            // Version en une ligne
            // $user->setPassword($hasher->hashPassword($user, $user->getpassword()));
            $user ->setRoles(['ROLE_USER','ROLE_ADMIN']);
            $em->persist($user);
            $em->flush();
            $message = 'Le compte a été ajouté en BDD!';
        }
        else{
            $message = 'Informations incorrectes.';
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'message' => $message,
        ]);
    }
    #[Route('/register/update/', name: 'app_register_update')]
    public function updateUser(Request $request, EntityManagerInterface $em): Response
    {
        $message = '';
        $user = $this->getUser();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() AND $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $message = 'Le compte a bien été modifié en BDD!';
        }
        else{
            $message = 'Informations incorrectes.';
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'message' => $message,
        ]);
    }
}
