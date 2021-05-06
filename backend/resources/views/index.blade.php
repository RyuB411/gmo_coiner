<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('/css/all.css') }}">
        <script src="https://unpkg.com/vue-chartjs/dist/vue-chartjs.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
        <title>Laravel</title>
    </head>
    <body class="antialiased">
        @include('navbar')
        <div id="app">
            <ticker-list class="py-2"></ticker-list>
            <div class="container  py-2">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">News</a>
                    </li>
                </ul>
            </div>
            <news-component class="bg-light" style="height: 60vh; overflow: visible scroll;"></news-component>
        </div>
        <script src=" {{ mix('js/app.js') }}" ></script>
    </body>
</html>
