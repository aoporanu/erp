<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class Order
 * @method static find(ParameterBag $json)
 * @method static create(array $all)
 * @method static paginate(int $int)
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

    /**
     * Unfinished
     *
     * @param $order
     * @return bool
     */
    public static function populatedOK($order)
    {
        foreach ($order->product() as $product) {
            // daca grat % priced din mechanism pentru $order->product
            if (!Mechanism::hasPromo($product)) {
                return;
            }
        }
        return true;
    }
}
