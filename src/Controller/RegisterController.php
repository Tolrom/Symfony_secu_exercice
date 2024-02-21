<?php

namespace App\Controller;

use App\Service\RegisterService;
use App\Service\UtilsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository; 
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegisterType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\EmailService;

class RegisterController extends AbstractController
{
    private EmailService $emailService;
    private RegisterService $registerService;
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hash;
    
    public function __construct(EntityManagerInterface $em, EmailService $emailService, RegisterService $registerService, UserPasswordHasherInterface $hash){
        $this->em = $em;
        $this->emailService = $emailService;
        $this->registerService = $registerService;
        $this->hash = $hash;
    }
    #[Route('/register/mail', name: 'app_register_test')]
    public function testParams(): Response{
        return new Response($this->emailService->testConfig());
    }
    #[Route('/register/add', name: 'app_register_add')]
    public function addUser(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $message = '';
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() AND $form->isValid()) {
            // dd($request->get('register'));
            $email = UtilsService::cleanInputs($request->get('register')['email']);
            $firstname = UtilsService::cleanInputs($request->get('register')['firstname']);
            $lastname = UtilsService::cleanInputs($request->get('register')['lastname']);
            $pass = UtilsService::cleanInputs($request->get('register')['password']['first']);
            // dd($email, $firstname, $lastname, $pass, isset($email));
            if(isset($email) and isset($firstname) and isset($lastname) and isset($pass)) {
                if(UtilsService::testRegex($email, $this->getParameter('regex_mail'))){
                    if(UtilsService::testRegex($pass, $this->getParameter('regex_password'))){
                        if(!$this->registerService->getUserByMail($email)){
                            $user->setIsActivated(false);
                            $user->setRoles(["ROLE_USER"]);
                            $user->setPassword($this->hash->hashPassword($user, $pass));
                            $user->setEmail($email);
                            $user->setFirstname($firstname);
                            $user->setLastname($lastname);
                            $this->em->persist($user);
                            $this->em->flush();
                            $this->emailService->sendEmail($email, 'validation', 'Pour valider votre compte, cliquez sur <a href="http://localhost:8000/register/activate/'.$user->getId().'">ce lien</a>');
                            $message = "Un lien de validation vient de vous être envoyé par mail.";
                        }
                        else {
                            $message = "Cette adresse email est déjà associée à un compte dans la BDD.";
                        }
                    }
                    else {
                        $message = "Le mot de passe n'est pas conforme, il doit comporter une minuscule, une majuscule, un chiffre et plus de 12 caractères.";
                    }
                }
                else {
                    $message = "L'adresse email n'est pas conforme";
                }
            }
            else {
                $message = "Les champs ne sont pas tous remplis";
            }
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
    #[Route('/register/activate/{id}', name: 'app_activate')]
    public function activateUser(mixed $id): Response {
        // Test si ID est bien un entier
        if(is_numeric($id)){
            // Test si le compte existe
            $user = $this->registerService->getUserById($id);
            if($user){
                $user->setIsActivated(true);
                $this->registerService->updateUser($user);
                // Rediriger vers la connexion
                return $this->redirectToRoute('app_login');
            }
            else {
                return $this->redirectToRoute('app_register_add');
            }
        }
        else {
            return $this->redirectToRoute('app_register_add');
        }
    }
}
