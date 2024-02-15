<?php
namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class WeatherService {
    private HttpClientInterface $client;
    private string $apiKey;
    public function __construct($apiKey, HttpClientInterface $client){
        $this->apiKey = $apiKey;
        $this->client = $client;
    }
    public function testKey(): string{
        return $this->apiKey;
    }
    public function getWeather(){
        $response = $this->client->request("GET","https://api.openweathermap.org/data/2.5/weather?lon=1.44&lat=43.6&units=metric&lang=fr&appid=".$this->apiKey);
        // $data = $response->getContent();
        $data = $response->toArray();
        return $data;
    }
    public function getCityWeather(?string $city){
        $response = $this->client->request("GET","https://api.openweathermap.org/data/2.5/weather?q=".$city."&units=metric&lang=fr&appid=".$this->apiKey);
        try {
            $data = $response->toArray();
            return $data;
        } catch (\Exception $e) {
            $data = [];
            $data = ['cod' => $e->getCode()];
            return $data;
        }
    }
}