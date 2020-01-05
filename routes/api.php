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

Route::apiResource('/orders', 'OrdersController');

Route::get('/orders/{order}/populate', 'OrdersController@populate');

Route::post('/orders/add-to-order', 'OrdersController@addToOrder');
Route::get('/orders/{order}/process', 'OrdersController@processOrder');
Route::get('/orders/{order}/first-step', 'OrdersController@firstStep');
Route::get('/orders/{order}/print', 'OrdersController@print');
Route::get('/orders/{order}/remove-item', 'OrdersController@removeItem');
