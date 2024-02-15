<?php
    namespace App\Controller;
    
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use App\Service\RandomService;

    class HomeController extends AbstractController{
        public function __construct(RandomService $randomService){
            $this->randomService = $randomService;
        }
        public function sayHello():Response{
            return new Response("<h1>Bonjour</h1>");
        }
        
        #[Route('/feur', name: 'app_feur')]
        public function sayFeur():Response{
            return new Response("<h1>Feur!</h1>");
        }

        #[Route('/add/{nbr1}/{nbr2}', name: 'app_add')]
        public function addNumbers(int $nbr1,int $nbr2):Response{
            return new Response("<h1>".$nbr1."+".$nbr2."=".$nbr1+$nbr2."</h1>");
        }
        #[Route("/", name:"app_home")]
        public function home(RandomService $randomService): Response{
            $message = '';
            $norris = [];
            $norris = $randomService->getChuckfact();
            $waifu = [];
            $waifu = $randomService->getWaifu();
            // dd($waifu);
            return $this->render('index.html.twig', [
                'norris'=> $norris,
                'waifu'=> $waifu,
            ]);
        }
    }