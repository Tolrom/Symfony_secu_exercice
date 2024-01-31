<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\User;
use App\Entity\Category;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    //attribut pour stocker la classe UserPasswordHasherInterface
    private UserPasswordHasherInterface $passwordHasher;
    //injection de dépendance (UserPasswordHasherInterface) dans le constructeur
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;    
    }
    public function load(ObjectManager $manager): void
    {
        //liste d'utilisateurs
        $users = [];
        //liste de mots de passe
        $passwords = [];
        // Liste des categories 
        $categories = [];
        //instance de la classe Faker
        $faker = Faker\Factory::create('fr_FR');
        // Boucle de création de catégories
        for ($i = 0; $i < 30; $i++) {
            $cat = new Category();
            $cat->setName($faker->word());
            //persister l'objet categorie
            $manager->persist($cat);
            //ajouter l'objet categorie dans le tableau
            $categories[]= $cat;
        }
        //boucle de création d'utilisateurs
        for ($i=0; $i < 50; $i++) { 
            //stocker le mot de passe dans une variable
            $password = $faker->jobTitle();
            //ajouter le mot de passe dans le tableau
            $passwords[]= $password;
            //instancier un objet utilisateur
            $user = new User();
            //setter les propriétés de l'objet utilisateur
            $user->setFirstname($faker->firstName('male'|'female'))
                ->setLastname($faker->lastName())
                ->setEmail($faker->email())
                ->setPassword($this->passwordHasher->hashPassword($user,$password))
                ->setRoles(['ROLE_USER']);
            //persister l'objet utilisateur
            $manager->persist($user);
            //ajouter l'objet utilisateur dans le tableau
            $users[]= $user;
        }
        //boucle de création d'articles
        for ($i=0; $i < 200; $i++) { 
            //instancier un objet article
            $article = new Article();
            //setter les propriétés de l'objet article
            $article->setTitle($faker->word())
                ->setContent($faker->paragraph())
                ->setCreationDate(new \DateTimeImmutable($faker->date('Y-m-d')))
                ->setUser($users[$faker->numberBetween(0,49)]);
            $cat1 = $categories;
            for ($j = 0; $j < 3; $j++) {
                $ran = $faker->numberBetween(0,count($cat1)-1);
                $article->addCategory($cat1[$ran]);
                unset($cat1[$ran]);
                sort($cat1);
                // $tab = array_values($categories);
                // $categories = $tab;
            }
            //persister l'objet article
            $manager->persist($article);
        }
        //enregistrer les objets en base de données
        $manager->flush();
    }
}
