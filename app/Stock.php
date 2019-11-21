<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Stock
 * A stock has products
 * A stock belongs to a location
 *  - FREE
 *  - PAID
 *  - Best Before
 *  - Mobile administrations (gestiune)
 * @property int qty
 * @package App
 * @method static paginate(int $int)
 * @method static create(array $all)
 * @method static findOrFail(int $id)
 * @method update(array $attributes = [])
 * @method static firstOrCreate(Product $product)
 */
class Stock extends Model
{
    protected $fillable = [
        'name', 'product_id', 'qty', 'lot'
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
     * @return HasMany
     */
    public function locations()
    {
        return $this->hasMany(Location::class);
    }

     /**
     * @param $qty
     * @param $stock
     */
    public function depleteStock(int $qty, Stock $stock): void
    {
        $stock->qty -= $qty;
        $stock->save();
    }

    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
