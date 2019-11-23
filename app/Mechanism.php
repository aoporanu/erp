<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static find($from_location)
 */
class Mechanism extends Model
{
    /**
     * @return BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * @param $product
     * @return int
     */
    public static function promoOK($product)
    {
        $mechanism = self::find($product->from_location);
        if($product->price == 0) {
            return 1;
        }
        if ($product % $mechanism->priced == 0) {
            return 0;
        }
        return 1;
    }
}
