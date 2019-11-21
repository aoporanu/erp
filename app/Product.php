<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'lot', 'weight', 'price', 'stock_id'
    ];

    /**
     * @return BelongsToMany
     */
    public function location()
    {
        return $this->belongsToMany(Location::class);
    }
}
