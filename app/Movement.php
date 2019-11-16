<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'stock_id',
        'product_id',
        'moved_to'
    ];
}
