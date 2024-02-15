<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\WeatherService;
use App\Form\WeatherType;

class WeatherController extends AbstractController
{
    // #[Route('/weather', name: 'app_weather')]
    // public function index(): Response
    // {
    //     return $this->render('weather/index.html.twig', [
    //         'controller_name' => 'WeatherController',
    //     ]);
    // }
    public function __construct(WeatherService $weatherService){
        $this->weatherService = $weatherService;
    }
    #[Route('/weather/', name:'app_weather')]
    public function showWeather(WeatherService $weatherService): Response{
        $weather = $weatherService->getWeather();
        // dd($weather);
        return $this->render('weather/index.html.twig', [
            'weather' => $weather,
        ]);
    }
    #[Route('/weather/city', name:'app_weather_city')]
    public function showCityWeather(Request $request, WeatherService $weatherService): Response{
        $message = '';
        $weather = [];
        $form = $this->createForm(WeatherType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $weather = $weatherService->getCityWeather($form->getData()['city']);
        }
        return $this->render('weather/cityWeather.html.twig', [
            'weather' => $weather,
            'form' => $form->createView(),
            'message'=> $message,
        ]);
    }
}
