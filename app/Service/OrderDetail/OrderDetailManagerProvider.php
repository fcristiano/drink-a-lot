<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 14:42
 */

namespace App\Service\OrderDetail;


use App\Database\Order\Order;
use App\Database\Order\OrderRepository;
use App\Database\OrderDetail\OrderDetail;
use App\Database\OrderDetail\OrderDetailRepository;
use App\Service\TheCocktailDb\TheCocktailDbClient;
use Fcristiano\LaravelCommon\Services\Repository\Factory\RepositoryFactory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class OrderDetailManagerProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OrderDetailManager::class, function(Application $app) {

            /** @var TheCocktailDbClient $theCocktailBarDbClient */
            $theCocktailBarDbClient = resolve(TheCocktailDbClient::class);

            /** @var RepositoryFactory $repositoryFactory */
            $repositoryFactory = resolve(RepositoryFactory::class);

            /**
             * @var OrderRepository $orderRepository
             * @var OrderDetailRepository $orderDetailRepository
             */
            $orderRepository        = $repositoryFactory->get(Order::class);
            $orderDetailRepository  = $repositoryFactory->get(OrderDetail::class);

            return new OrderDetailManagerConcrete($theCocktailBarDbClient, $orderRepository, $orderDetailRepository);
        });
    }
}