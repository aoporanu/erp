<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/products', 'ProductsController');
Route::post('/products/search', 'ProductsController@search');
Route::post('/products/add-to-stock', 'ProductsController@addProductToStock');

Route::apiResource('/stocks', 'StocksController');
Route::post('/stocks/add-on-location', 'StocksController@addStockOnLocation');

Route::apiResource('/locations', 'LocationsController');
