<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandleSticksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candle_sticks', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('interval');
            $table->datetime('open_time');
            $table->boolean('line_direction');
            $table->unsignedDecimal('open_price', 15, 5);
            $table->unsignedDecimal('close_price', 15, 5);
            $table->unsignedDecimal('high_price', 15, 5);
            $table->unsignedDecimal('low_price', 15, 5);
            $table->double('volume', 15, 5);
            $table->unique(
                ['symbol', 'interval', 'open_time'],
                'symbol_interval_time_unique'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candle_sticks');
    }
}
