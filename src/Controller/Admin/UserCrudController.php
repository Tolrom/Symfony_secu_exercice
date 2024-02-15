<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstname','Prénom'),
            TextField::new('lastname','Nom'),
            TextField::new('email','Adresse Mail'),
            ArrayField::new('roles','Rôles'),
            ImageField::new('img','Image')->setBasePath('/images')
                                        ->setUploadDir('public/images')
                                        ->setUploadedFileNamePattern('[year][month][day][contenthash].[extension]'),
        ];
    }
    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setPageTitle('index', 'Liste des utilisateurs')
            ->setPageTitle('edit', 'Modifier un utilisateur')
            ->setPageTitle('new', 'Ajouter un utilisateur')
    ;}
}
