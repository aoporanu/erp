<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'name',
    ];

    public function addProduct(Product $product)
    {
        return Stock::firstOrCreate($product);
    }
}
