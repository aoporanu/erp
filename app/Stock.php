<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Stock
 * A stock has products
 * A stock belongs to a location
 *  - FREE
 *  - PAID
 *  - Best Before
 *  - Mobile administrations (gestiune)
 * @package App
 */
class Stock extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * @param Product $product
     * @return mixed
     */
    public function addProduct(Product $product)
    {
        return Stock::firstOrCreate($product);
    }

    /**
     * @return BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
