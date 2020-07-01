<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1'], function() {

    Route::group(['prefix' => 'customer'], function() {
        /* Order */
        Route::get(   'orders/{orderId}',   'Api\Customer\OrderController@get');
        Route::post(  'orders',             'Api\Customer\OrderController@create');
        Route::patch( 'orders/{orderId}',   'Api\Customer\OrderController@update');
        Route::delete('orders/{orderId}',   'Api\Customer\OrderController@delete');


        Route::group(['prefix' => 'orders/{orderId}'], function() {
            /* OrderDetail */
            Route::get(   'details',            'Api\Customer\OrderDetailController@getList');
            Route::post(  'details',            'Api\Customer\OrderDetailController@create');
            Route::delete('details/{detailId}', 'Api\Customer\OrderDetailController@delete');
        });
    });


    /* Drink */
    Route::get('drinks',              'Api\Drink\DrinkController@getList');
    Route::get('drinks/{drinkId}',    'Api\Drink\DrinkController@get');

    /* Ingredient */
    Route::get('ingredients',                   'Api\Drink\IngredientController@getList');
    Route::get('ingredients/{ingredientId}',    'Api\Drink\IngredientController@get');

});