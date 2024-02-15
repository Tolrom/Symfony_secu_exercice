<?php
namespace App\Service;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use PhpParser\Node\Expr\Throw_;

class CategoryService{
    private CategoryRepository $categoryRepository;
    private EntityManagerInterface $em;
    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $em){
        $this->categoryRepository = $categoryRepository;
        $this->em = $em;
    } 
    public function getAllCategories(): array|bool{
        $data = $this->categoryRepository->findAll();
        if(!$data){
            $data = false;
        }
        return $data;
    }
    public function getCategoryById(int $id): Category|array{
        try{
        $data = $this->categoryRepository->find($id);
        if(!$data){
            throw new \Exception("Cette catégorie n'existe pas");
        }
        }
        catch(\Exception $e){
            $data = ['error'=>$e->getMessage()];
        }
        return $data;
    }
    public function getCategoryByName(string $name): ?Category{
        return $this->categoryRepository->findOneBy(['name'=>$name]);
    }
    public function insertCategory(Category $category): bool{
        if(!$category){
            return false;
        }
        else{
            $this->em->persist($category);
            $this->em->flush();
            return true;
        }
    }

}