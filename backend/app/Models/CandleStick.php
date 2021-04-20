<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandleStick extends Model
{
    use HasFactory;

    protected $fillable = [
        'crypt_type',
        'interval',
        'open_time',
        'line_direction',
        'open_price',
        'close_price',
        'high_price',
        'low_price',
        'volume',
    ];
}
