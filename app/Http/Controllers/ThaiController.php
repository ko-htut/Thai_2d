<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\DomCrawler\Crawler;
use \Illuminate\Routing\Controller;

class ThaiController extends Controller
{
    public function stock(){
        $client = new Client(['base_uri' => 'https://marketdata.set.or.th/','verify' => false]);
        $html = $client->request("GET",'mkt/marketsummary.do');
        $crawler = new Crawler($html->getBody()->getContents());
        $data = $crawler->filter('tbody')->filter('tr')->first()->filter('td')->each(function (Crawler $node,$i) {
                return $node->innerText();
            });
        return json_encode($data);    
    }
}