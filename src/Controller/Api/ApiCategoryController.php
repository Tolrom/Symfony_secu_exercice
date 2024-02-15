<?php
namespace App\Controller\Api;
use App\Service\CategoryService;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ApiCategoryController extends AbstractController {
    private CategoryService $categoryService;
    private SerializerInterface $serializer;
    public function __construct(CategoryService $categoryService, SerializerInterface $serializer) {
        $this->categoryService = $categoryService;
        $this->serializer = $serializer;
    }
    #[Route(path:"/api/cat", name:"app_api_cat_test", methods:'GET')]
    public function testApi(): Response {
        return $this->json($this->categoryService->getAllCategories(),
                            200,
                            ['Access-Control-Allow-Origin'=> '*',
                            'Content-type' =>'application/json'],
                            ['groups'=> 'toutes']
                        );
    }
    #[Route(path:"/api/cat/{id}", name:"app_api_cat_test", methods:'GET' )]
    public function testApiById($id): Response {
        return $this->json($this->categoryService->getCategoryById($id),
                            200,
                            ['Access-Control-Allow-Origin'=> '*',
                            'Content-type' =>'application/json'],
                            ['groups'=> 'toutes']
                        );
    }
    #[Route(path:'/api/cat', name:'app_api_cat_add', methods:'POST')]
    public function addCategory(Request $request): Response {
        // Récupération du json
        $json = $request->getContent();
        if($json){
            // Transformation en tableau
            $data = $this->serializer->decode($json,'json');
            $cat = new Category();
            $cat->setName($data['name']);
            $this->categoryService->insertCategory($cat);
        }
        return $this->json($cat,200,
        ['Access-Control-Allow-Origin'=> '*',
        'Content-type' =>'application/json'],
        ['groups'=> 'unique']
    );
    }
}