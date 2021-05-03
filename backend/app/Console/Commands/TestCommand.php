<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SlackNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slack:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $client;
    private $message;
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
        $this->url_base = sprintf('%s%s', config('app.gmo.public_url'), 'v1/ticker');
        

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $param_list = [
            'symbol' => '',
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

            $this->message = sprintf("■最新レート:%s\n", now()->format('m/d H:i'));
            $this->message .= "----------------\n";
            collect($posts['data'])->each(function ($data) {
                if (strpos($data['symbol'], 'JPY')) {
                    $this->message .= sprintf("%s\t:%s\t(%s-%s)\n", $data['symbol'], $data['last'], $data['high'], $data['low']);
                }
            });
            \Slack::send($this->message);
        } catch (ClientException $e) {
            return false;
        }
        return true;
    }
}
