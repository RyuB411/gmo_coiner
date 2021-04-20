<?php

namespace App\Console\Commands;

use App\Models\CandleStick;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GetKline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmoapi:getkline {symbol=BTC} {date?} {interval?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $client;
    private $interval;
    private $date;
    private $url_base;
    private $interval_day_list;
    private $interval_year_list;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->client = new Client();

        $this->interval_day_list = collect([
            // '1min',
            // '5min',
            '10min',
            // '15min',
            '30min',
            '1hour',
        ]);
        $this->interval_year_list = collect([
            '4hour',
            '8hour',
            '12hour',
            '1day',
            '1week',
            '1month',
        ]);

        $this->url_base = sprintf('%s%s', config('app.gmo.public_url'), 'v1/klines');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // TODO:引数の書式チェック
        $this->symbol = $this->argument('symbol');
        if ($this->argument('interval')) {
            $this->interval = $this->argument('interval');
        } else {
            $this->firstCreate();
            return;
        }
        if ($this->argument('date')) {
            $this->date = $this->argument('date');
        } else {
            if ($this->interval_day_list->contains($this->interval)) {
                $this->date = today()->format('Ymd');
            } else {
                $this->date = today()->format('Y');
            }
        }

        // TODO:エラーチェック
        $this->requestKline($this->symbol, $this->interval, $this->date);
        return 0;
    }

    private function requestKline($symbol, $interval, $date) {
        $param_list = [
            'symbol' => $symbol,
            'interval' => $interval,
            'date' => $date,
        ];

        $url = sprintf('%s?%s', $this->url_base, http_build_query($param_list));
        $method = 'GET';
        try {
            $response = $this->client->request($method, $url);
            $posts = $response->getBody();
            $posts = json_decode($posts, true);
            if ($posts['status']) {
                return false;
            }
        } catch (ClientException $e) {
            return false;
        }

        $this->registerCandleStick(collect($posts['data']), $interval);
        return true;
    }

    private function registerCandleStick($data, $interval) {
        $data->each(function ($data) use ($interval) {
            $record = collect();
            $record->prepend(Carbon::parse($data['openTime']/1000)->format('Y-m-d H:i'), 'open_time');
            $record->prepend($interval, 'interval');
            $record->prepend($this->argument('symbol'), 'symbol');
            $record->prepend($data['open'], 'open_price');
            $record->prepend($data['close'], 'close_price');
            $record->prepend($data['high'], 'high_price');
            $record->prepend($data['low'], 'low_price');
            $line_direction = $data['close'] >= $data['open'];
            $record->prepend($line_direction, 'line_direction');
            $record->prepend($data['volume'], 'volume');

            CandleStick::updateOrCreate(
                $record->only(['symbol', 'interval', 'open_time'])->toArray(),
                $record->toArray()
            );
        });
    }

    private function firstCreate() {
        $this->interval_year_list->each(function ($interval) {
            // 最も古い通貨で2018年から取り扱い開始
            $date = Carbon::parse('2018-01-01');
            while (!$date->isFuture()) {
                $this->requestKline($this->symbol, $interval, $date->format('Y'));
                $date->addYear();
            }
        });
        $this->interval_day_list->each(function ($interval) {
            // 2021/4/15から日単位集計開始
            $date = Carbon::parse('2021-04-15');
            while (!$date->isFuture()) {
                $this->requestKline($this->symbol, $interval, $date->format('Ymd'));
                $date->addDay();
            }
        });
    }
}
