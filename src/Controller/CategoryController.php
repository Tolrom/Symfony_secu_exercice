<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CategoryType;

class CategoryController extends AbstractController
{
    #[Route('/category/add', name: 'app_category_add')]
    public function addCategory(Request $request, EntityManagerInterface $em): Response
    {
        $message = '';
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($category);
            $em->flush();
            $message = 'La catégorie a bien été ajoutée';
        }
        return $this->render('category/index.html.twig', [
            'form' => $form->createView(),
            'message' => $message,
        ]);
    }
}
