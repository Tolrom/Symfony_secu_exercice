<?php
namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RandomService {
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client) {
        $this->client = $client;
    }

    public function getChuckFact(){
        $response = $this->client->request("GET","https://api.chucknorris.io/jokes/random");
        try {
            $data = $response->toArray();
            return $data;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
    public function getWaifu(){
        $response = $this->client->request("GET","https://api.waifu.im/search?included_tags=waifu");
        try {
            $data = $response->toArray();
            return $data;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
}