<? 

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\DomCrawler\Crawler;
use \Illuminate\Routing\Controller;
class Stock extends Controller {

    public function stock(){
        $client = new Client(['base_uri' => 'https://marketdata.set.or.th/','verify' => false]);
        $html = $client->request("GET",'mkt/marketsummary.do');
        $crawler = new Crawler($html->getBody()->getContents());
        $crawler->filter('tbody > tr')->each(function($tr) {
            $tds = $tr->filter('td');
            $stock = new Stock();
            $stock->stock_code = $tds->eq(0)->text();
            $stock->stock_name = $tds->eq(1)->text();
            $stock->stock_price = $tds->eq(2)->text();
            $stock->stock_change = $tds->eq(3)->text();
            $stock->stock_percent = $tds->eq(4)->text();
            $stock->save();
             return json_encode($stock);    
        });
        // $data = $crawler->filter('tbody')->filter('tr')->first()->filter('td')->each(function (Crawler $node,$i) {
        //         return $node->innerText();
        //     });
        // return json_encode($data);    
    }

}
 
