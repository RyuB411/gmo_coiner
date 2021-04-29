<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CandleStick;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CandleStickController extends Controller
{
    public function index($symbol, $interval='4hour') {
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
            ->limit(240)
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
}
