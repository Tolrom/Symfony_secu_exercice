<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExempleController extends AbstractController
{
    #[Route('/exemple', name: 'app_exemple')]
    public function index(): Response
    {
        return $this->render('exemple/index.html.twig', [
            'proc' => 'Quoi ???',
            'test1' => 'Feur !',
            'test2' => 'Quoicoubeh !',
        ]);
    }

    #[Route('/calcul/{nbr1}/{op}/{nbr2}', name: 'app_calcul')]
    public function calculatrice(int $nbr1, int $nbr2, string $op): Response
    {
        return $this->render('exemple/calcul.html.twig', [
            'nbr1' => $nbr1,
            'nbr2' => $nbr2,
            'op' => $op,
        ]);
    }
}
