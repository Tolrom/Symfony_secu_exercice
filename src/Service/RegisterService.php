<?php
namespace App\Service;
use Doctrine\ORM\EntityManager;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class RegisterService{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository){
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(): ?array{
        $users = $this->userRepository->findAll();
        if(!$users){
            $users = null;
        }
        return $users;
    }
    public function getUserById(int $id): ?User{
        $user = $this->userRepository->find($id);
        if(!$user){
            $user = null;
        }
        return $user;
    }
    public function getUserByMail(string $email): ?User {
        $user = $this->userRepository->findOneBy(['email'=>$email]);
        if(!$user){
            $user = null;
        }
        return $user;
    }
    public function insertUser(?User $user): bool{
        if(!$user){
            return false;
        }
        $this->em->persist($user);
        $this->em->flush();
        return true;
    }
    public function updateUser(?User $user): bool{
        if(!$user){
            return false;
        }
        $this->em->persist($user);
        $this->em->flush();
        return true;
    }
}