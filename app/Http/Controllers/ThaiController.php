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
        $date = $crawler->filter('table caption')->text();
        $date = explode(' ',$date);
        $date = $date[5].' '.$date[6];
        $data = $crawler->filter('tbody')->filter('tr')->first()->filter('td')->each(function (Crawler $node,$i) {
                return $node->innerText();
            });
        return response()->json(
             [
                "time" => $date,
                "index" => $data[0],
                "latest" => $data[1],
                "change" => $data[2],
                "per_change" => $data[3],
                "maximum" => $data[4],
                "lowest" => $data[5],
                "amount" => $data[6],
                "value" => $data[7],
            ]    
        );

    }
}