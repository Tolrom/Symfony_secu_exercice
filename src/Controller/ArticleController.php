<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    public function __construct(ArticleRepository $articleRepository){
        $this->articleRepository = $articleRepository;
    }

    #[Route('/article/all', name: 'app_article_show_all')]
    public function showAllArticles(): Response{
        $articles = $this->articleRepository->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }
    #[Route('/article/id/{id}', name: 'app_article')]
    public function showArticleById($id): Response{
        $article = $this->articleRepository->find($id);
        return $this->render('article/article.html.twig', [
            'article'=> $article,
        ]);
    }
    #[Route('/article/add', name:'app_article_add')]
    public function addArticle(Request $request, EntityManagerInterface $em, ArticleRepository $repo): Response{
        $message = '';
        $article = new Article();
        $currentUser = $this->getUser();
        $article->setUser($currentUser);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($repo->findOneBy(['creationDate'=>$form->getData()->getCreationDate()])){
                $message = 'L\'article existe déjà dans la BDD.';
            }
            else {
                $em->persist($article);
                $em->flush();
                $message = 'L\'article a bien été ajouté!';
            }
        }
        else {
            $message = 'Informations incorrectes.';
        }
        return $this->render('article/addArticle.html.twig', [
            'form' => $form->createView(),
            'message' => $message,
        ]);
    }
    #[Route('/article/update/{id}', name:'app_article_update')]
    public function updateArticle(Request $request, EntityManagerInterface $em, ArticleRepository $repo, $id): Response{
        $message = '';
        $article = $repo->findOneBy(['id'=>$id]);
        $currentUser = $this->getUser();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($currentUser->getId() == $article->getUser()->getId() || $currentUser->getRoles() == 'ROLE_ADMIN'){
                $em->persist($article);
                $em->flush();
                $message = "L'article a bien été modifié";
            }
            else {
                $message = "Vous devez être l'auteur d'un article pour le modifier.";
            }
        }
        return $this->render('article/addArticle.html.twig', [
            'form' => $form->createView(),
            'message' => $message,
        ]);
    }
}
