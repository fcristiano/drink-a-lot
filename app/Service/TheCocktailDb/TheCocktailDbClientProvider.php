<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 16:31
 */

namespace App\Service\TheCocktailDb;


use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TheCocktailDbClientProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TheCocktailDbClient::class, function (Application $app) {

            $service = new TheCocktailDbClientConcrete(new Client());

            return $service;
        });
    }
}