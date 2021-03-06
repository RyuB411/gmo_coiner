<?php

use App\Http\Controllers\API\CandleStickController;
use App\Http\Controllers\API\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/candle_stick/{symbol}', [CandleStickController::class, 'index']);

Route::get('/ticker/{symbol}', [CandleStickController::class, 'ticker']);

Route::get('/news', [NewsController::class, 'index']);
