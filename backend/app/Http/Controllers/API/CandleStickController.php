<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CandleStick;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\ClientException;

class CandleStickController extends Controller
{
    public function index($symbol, $interval='1hour', $limit=180) {
        $post_data = collect(
            CandleStick::
            where('symbol', $symbol)
            ->where('interval', $interval)
            ->select(
                'open_price',
                'high_price',
                'low_price',
                'close_price',
                'open_time',
                'volume'
            )
            ->orderBy('open_time', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($data) {
                return [
                    'o' => (float)$data->open_price,
                    'h' => (float)$data->high_price,
                    'l' => (float)$data->low_price,
                    'c' => (float)$data->close_price,
                    't' => Carbon::parse($data->open_time)->getTimestamp() * 1000,
                    'v' => (float)$data->volume,
                ];
            })
        );

        $sma = collect([7, 20, 60])->mapWithKeys(function ($day) use ($post_data) {
            $result = $post_data->map(function ($data) use ($day, $post_data) {
                $diff = $post_data->where('t', '<=', $data['t'])->take($day);
                if ($diff->count() != $day) {
                    return null;
                }
                    
                return 
                (object)[
                    'y' => round($diff->average('c'), 3),
                    'x' => $data['t'],
                ];
            })
            ->reject(function ($data) {
                return empty($data);
            });
            return [(string)$day => $result];
        });
        $price_min = $post_data->min('l');
        $volume_display_max = ($price_min + $price_min / 10);
        $volume_display_min = ($price_min - $price_min / 10);
        $volume_max = $post_data->max('v');
        $volume = $post_data->map(function ($data) use ($volume_max, $volume_display_max, $volume_display_min) {
            $volume =  $volume_display_min + $data['v'] / $volume_max * ($volume_display_max - $volume_display_min);
            return (object)[
                'y' => $volume,
                'x' => $data['t'],
            ];
        });
        return [
            'ohlc' => $post_data->map(function($data){ return (object)$data; })->toArray(),
            'sma' => $sma->toArray(),
            'volume' => $volume
        ];
    }

    public function ticker($symbol = 'ALL') {
        if ($symbol == 'ALL') {
            return config('const.CRYPT.SYMBOL_LIST')->map(function($symbol) {
                return $this->getTickerInfo($symbol);
            });
        } else {
            return $this->getTickerInfo($symbol);
        }
    }

    private function getTickerInfo($symbol) {
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

            $today_data['sma'] = collect([7, 20, 60])->mapWithKeys(function($day) use ($data, $today_data) {
                $result = [];
                $result['day'] = $day;
                $price = round($data->sortDesc()->take($day)->average('close'), 3);
                $result['price'] = $price >= 1000 ? number_format($price) : $price;
                $result['direction'] = $price > $today_data['close'] ? 'text-danger' : 'text-success';
                return [(string)$day => $result];
            })->toArray();
            $today_data['symbol'] = $symbol;

            $after_day_value = round($today_data['close'] - $before_close, 3);
            $after_day_value = abs($after_day_value) >= 1000 ? number_format($after_day_value) : $after_day_value;
            $today_data['after_day_ratio'] = sprintf('%.2f', $after_day_ratio);
            $today_data['after_day_value'] = sprintf('%s', $after_day_value);

            if ($diff_hb > $diff_hc) {
                $today_data['ratio_from'] = sprintf('%d%%', ($diff_hc / $diff_hl) * 100);
                $ratio_to = ($diff_hb - $diff_hc) / $diff_hl * 100;
                $ratio_to = $ratio_to < 1 ? 1 : $ratio_to;
                $today_data['ratio_to'] = sprintf('%d%%', $ratio_to);
                $today_data['after_direction_text'] = 'text-danger';
                $today_data['after_direction_bg'] = 'bg-danger';
            } else {
                $today_data['ratio_from'] = sprintf('%d%%', ($diff_hb / $diff_hl) * 100);
                $ratio_to = ($diff_hc - $diff_hb) / $diff_hl * 100;
                $ratio_to = $ratio_to < 1 ? 1 : $ratio_to;
                $today_data['ratio_to'] = sprintf('%u%%', $ratio_to);
                $today_data['after_direction_text'] = 'text-success';
                $today_data['after_direction_bg'] = 'bg-success';
            }
            if ($today_data['close'] >= 1000) {
                $today_data['close'] =  number_format($today_data['close']);
            }
            return $today_data;
        } catch (ClientException $e) {
            return false;
        }
    }
}
