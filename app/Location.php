<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * @param $location
     * @param $stock
     * @param $qty
     * @return bool
     */
    public function createStockOnLocation($location, $stock, $qty)
    {
        return self::save(['location_name' => $location->name, 'stock_id' => $stock->id, 'qty' => $qty]);
    }
}
