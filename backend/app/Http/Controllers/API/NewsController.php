<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Weidner\Goutte\GoutteFacade;

class NewsController extends Controller
{
    private $url_base = 'https://news.google.com/';

    public function index($symbol, $interval='1hour') {
        return $this->getNewsInfo();
    }

    private function getNewsInfo() {
        $param_list = [
            'q' => '仮想通貨 when:1d',
            'hl' => 'ja',
            'gl' => 'JP',
            'ceid' => 'JP%3Aja',
        ];

        $url = sprintf('%s%s?%s', $this->url_base, 'search', http_build_query($param_list));
        $method = 'GET';
        $response =  GoutteFacade::request($method, $url);
        return $response->filter('div.NiLAwe')->each(function ($div) {
            $result['url'] = sprintf('%s%s', $this->url_base, $div->filter('a')->first()->attr('href'));
            $img = $div->filter('img');
            if ($img->count() > 0) {
                $result['img'] = $img->first()->attr('src');
            } else {
                $result['img'] = '/img/noimage.jpg';
            }
            $result['title'] = mb_strimwidth($div->filter('h3')->first()->text(), 0, 60, '…');
            $site = $div->filter('div.QmrVtf')->first();
            $result['site_name'] = $site->filter('a')->first()->text();
            $result['time'] = $site->filter('time')->first()->text();
            $result['description'] = $div->filter('div.Da10Tb')->first()->text();
            return $result;
        });
    }
}
