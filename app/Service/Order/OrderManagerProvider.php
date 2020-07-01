<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 14:42
 */

namespace App\Service\Order;


use App\Database\BarTable\BarTable;
use App\Database\BarTable\BarTableRepository;
use App\Database\Bartender\Bartender;
use App\Database\Bartender\BartenderRepository;
use App\Database\Order\Order;
use App\Database\Order\OrderRepository;
use App\Database\Waiter\Waiter;
use App\Database\Waiter\WaiterRepository;
use Fcristiano\LaravelCommon\Services\Repository\Factory\RepositoryFactory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class OrderManagerProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OrderManager::class, function(Application $app) {
            /** @var RepositoryFactory $repositoryFactory */
            $repositoryFactory = resolve(RepositoryFactory::class);

            /**
             * @var OrderRepository $orderRepository
             * @var BarTableRepository $barTableRepository
             * @var BartenderRepository $bartenderRepository
             * @var WaiterRepository $waiterRepository
             */
            $orderRepository     = $repositoryFactory->get(Order::class);
            $barTableRepository  = $repositoryFactory->get(BarTable::class);
            $bartenderRepository = $repositoryFactory->get(Bartender::class);
            $waiterRepository    = $repositoryFactory->get(Waiter::class);

            return new OrderManagerConcrete($orderRepository, $barTableRepository, $bartenderRepository, $waiterRepository);
        });
    }
}