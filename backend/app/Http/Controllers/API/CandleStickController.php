<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CandleStick;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\ClientException;

class CandleStickController extends Controller
{
    public function index($symbol, $interval='1hour') {
        $post_data = CandleStick::
            where('symbol', $symbol)
            ->where('interval', $interval)
            ->select(
                'open_price',
                'high_price',
                'low_price',
                'close_price',
                'open_time'
            )
            ->orderBy('open_time', 'desc')
            ->limit(120)
            ->get()
            ->map(function ($data) {
                return (object) [
                    'o' => (float)$data->open_price,
                    'h' => (float)$data->high_price,
                    'l' => (float)$data->low_price,
                    'c' => (float)$data->close_price,
                    't' => Carbon::parse($data->open_time)->getTimestamp() * 1000
                ];
            });
        return $post_data->toArray();
    }

    public function ticker($symbol='BTC') {
        $client = new Client();
        $url_base = sprintf('%s%s', config('app.gmo.public_url'), 'v1/klines');

        $param_list = [
            'symbol' => $symbol,
            'interval' => '1day',
            'date' => today()->format('Y'),
        ];

        $url = sprintf('%s?%s', $url_base, http_build_query($param_list));
        $method = 'GET';
        try {
            $response = $client->request($method, $url);
            $posts = $response->getBody();
            $posts = json_decode($posts, true);
            if ($posts['status']) {
                return false;
            }

            $data = collect($posts['data']);
            $before_close = $data->get($data->count()-2)['close'];
            $today_data = $data->last();

            $diff_hl = $today_data['high'] - $today_data['low'];
            $diff_hb = $before_close - $today_data['low'];
            $diff_hc = $today_data['close'] - $today_data['low'];

            $after_day_ratio = ($today_data['close'] / $before_close - 1) * 100;

            $today_data['symbol'] = $symbol;
            $today_data['after_day_ratio'] = sprintf('%.2f', $after_day_ratio);
            $today_data['after_day_value'] = sprintf('%.2f', $today_data['close'] - $before_close);

            if ($diff_hb > $diff_hc) {
                $today_data['ratio_from'] = sprintf('%d%%', ($diff_hc / $diff_hl) * 100);
                $today_data['ratio_to'] = sprintf('%d%%', (($diff_hb - $diff_hc) / $diff_hl) * 100);
                $today_data['after_direction_text'] = 'text-danger';
                $today_data['after_direction_bg'] = 'bg-danger';
            } else {
                $today_data['ratio_from'] = sprintf('%d%%', ($diff_hb / $diff_hl) * 100);
                $today_data['ratio_to'] = sprintf('%u%%', (($diff_hc - $diff_hb) / $diff_hl) * 100);
                $today_data['after_direction_text'] = 'text-success';
                $today_data['after_direction_bg'] = 'bg-success';
            }
            return $today_data;
        } catch (ClientException $e) {
            return false;
        }
    }
}
