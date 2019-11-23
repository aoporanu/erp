<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

/**
 * Class Order
 * @method static paginate
 * @method static find(\Symfony\Component\HttpFoundation\ParameterBag $json)
 * @method static create(array $all)
 * @property mixed status
 * @package App
 */
class Order extends Model
{
    protected $fillable = [
        'name', 'client', 'tp', 'creator', 'agent'
    ];

    /**
     * @param $orderId
     * @return array
     */
    public function getProducts($orderId)
    {
        $products = DB::select('select * from order_product where order_id=?', [$orderId]);

        return $products;
    }

    /**
     * @return BelongsTo
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * @return BelongsToMany
     */
    public function product()
    {
        return $this->belongsToMany(Product::class, 'order_product');
    }
}
