<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextField::new('content', 'Contenu'),
            CollectionField::new('categories','Catégories'),
            TextField::new('user','Auteur'),
            DateTimeField::new('creationDate','Date de création'),
        ];
    }
    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setPageTitle('index', 'Liste des articles')
            ->setPageTitle('edit', 'Modifier un article')
            ->setPageTitle('new', 'Ajouter un article')
    ;}
}
