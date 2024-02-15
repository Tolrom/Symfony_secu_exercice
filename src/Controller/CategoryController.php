<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CategoryType;
use App\Service\UtilsService;
use App\Service\CategoryService;

class CategoryController extends AbstractController
{
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService){
        $this->categoryService = $categoryService;
    }
    #[Route('/category/add', name: 'app_category_add')]
    public function addCategory(Request $request): Response
    {
        $message = '';
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
            if($form->isSubmitted() AND $form ->isValid()){
                if($this->categoryService->getCategoryByName($form->getData()->getName())){
                    $message = 'La catégorie existe déja en BDD';
                }
                else{
                    $category->setName(UtilsService::cleanInputs($form->getData()->getName()));
                    $this->categoryService->insertCategory($category);
                    $message = 'La catégorie a bien été ajoutée';
                }
            }
        return $this->render('category/index.html.twig', [
            'form' => $form->createView(),
            'message' => $message,
        ]);
    }
    #[Route('/category/all', name:'app_category_all')]
    public function showCategories(CategoryService $categoryService): Response{
        $categories = $categoryService->getAllCategories();
        if(!$categories){
            $categories[0] = new Category();
            $categories[0]->setName("Il n'y a pas de catégorie");
        }
        return $this->render('category/show_all_categories.html.twig',[
            'categories'=> $categories,
        ]);
    }
    #[Route('/category/{id}', name: 'category')]
    public function showCategoryById(CategoryService $categoryService,int $id): Response{
        $category = $categoryService->getCategoryById($id);
        return $this->render('category/category.html.twig', [
            'category'=> $category,
        ]);
    }
}
