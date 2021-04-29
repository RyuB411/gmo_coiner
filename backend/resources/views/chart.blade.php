<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <script src="https://unpkg.com/vue-chartjs/dist/vue-chartjs.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
        <title>Laravel</title>
    </head>
    <body class="antialiased">
        <div id="app">
            <candlestick-component></candlestick-component>
        </div>
        <script src=" {{ mix('js/app.js') }}" ></script>
    </body>
</html>

