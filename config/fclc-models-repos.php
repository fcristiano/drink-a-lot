<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 01:42
 */

return [
    'config' => [
        \App\Database\BarTable\BarTable::class              => \App\Database\BarTable\BarTableRepository::class,
        \App\Database\Bartender\Bartender::class            => \App\Database\Bartender\BartenderRepository::class,
        \App\Database\Order\Order::class                    => \App\Database\Order\OrderRepository::class,
        \App\Database\OrderDetail\OrderDetail::class        => \App\Database\OrderDetail\OrderDetailRepository::class,
        \App\Database\OrderStatusLog\OrderStatusLog::class  => \App\Database\OrderStatusLog\OrderStatusLogRepository::class,
        \App\Database\Waiter\Waiter::class                  => \App\Database\Waiter\WaiterRepository::class
    ]
];