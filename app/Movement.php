<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed location
 * @property string moved_to
 * @property mixed product_id
 * @property int stock_id
 */
class Movement extends Model
{
    protected $fillable = [
        'stock_id',
        'product_id',
        'moved_to'
    ];
}
