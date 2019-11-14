<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public function addProduct(Product $product)
    {
        return Stock::firstOrCreate($product);
    }
}
