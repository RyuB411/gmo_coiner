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
            $table->string('crypt_type');
            $table->string('interval');
            $table->datetime('open_time');
            $table->boolean('line_direction');
            $table->unsignedDecimal('open_price', 12, 10);
            $table->unsignedDecimal('close_price', 12, 10);
            $table->unsignedDecimal('high_price', 12, 10);
            $table->unsignedDecimal('low_price', 12, 10);
            $table->float('volume', 12, 10);
            $table->unique(
                ['crypt_type', 'interval', 'open_time'],
                'crypt_interval_time_unique'
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
