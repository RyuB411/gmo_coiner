<?php

return [
    'CRYPT' => [
        'SYMBOL_LIST' => collect([
            'BTC_JPY',
            'ETH_JPY',
            'BCH_JPY',
            'LTC_JPY',
            'XRP_JPY',
        ]),
        'INTERVAL_LIST' => [
            'DAILY' => collect([
                // '1min',
                // '5min',
                // '10min',
                // '15min',
                '30min',
                '1hour',
            ]),
            'YEAR' => collect([
                '4hour',
                '8hour',
                '12hour',
                '1day',
                '1week',
                '1month',
            ])
        ]
    ],
];
