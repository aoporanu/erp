<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'description',
        'parent',
        'product_id',
        'qty'
    ];

    /**
     * @param $location
     * @param $stock
     * @param $qty
     * @return bool
     * @throws \Throwable
     */
    public function createStockOnLocation($location, $stock, $qty)
    {
        $stockOnLocation = self::where([
            'name' => $location->name,
            'parent' => $stock->id,
            'product_id' => 1,
            'description' => $location->name . ' for ' . $stock->id
        ])->first();

        if ($stockOnLocation) {
            return $stockOnLocation->update(['qty' => $stockOnLocation->qty + $qty]);
        }

        return self::create([
            'name' => $location->name,
            'parent' => $stock->id,
            'qty' => $qty,
            'product_id' => 1,
            'description' => $location->name . ' for ' . $stock->id,
        ]);
    }
}
