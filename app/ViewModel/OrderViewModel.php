<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 14:38
 */

namespace App\ViewModel;


use App\Database\Order\Order;
use Fcristiano\LaravelCommon\ViewModel\AbstractViewModel;

class OrderViewModel extends AbstractViewModel
{
    /**
     * @param $order
     * @return array
     */
    public static function default($order)
    {
        if(!$order instanceof Order) {
            return null;
        }

        return [
            'id'            => $order->getId(),
            'bar_table'     => BarTableViewModel::slim($order->getBarTable()),
            'status'        => [
                'name'  => Order::getStatusName($order->getStatus())
            ],
            'bartender'     => BartenderViewModel::slim($order->getBartender()),
            'waiter'        => WaiterViewModel::slim($order->getWaiter()),
            'amount'        => $order->getAmount(),
            'created_at'    => self::date($order->getCreatedAt()),
            'updated_at'    => self::date($order->getUpdatedAt()),
        ];
    }

    /**
     * @param $order
     * @return array
     */
    public static function full($order)
    {
        if(!$order instanceof Order) {
            return null;
        }

        return [
            'id'            => $order->getId(),
            'bar_table'     => BarTableViewModel::default($order->getBarTable()),
            'status'        => [
                'value' => $order->getStatus(),
                'name'  => Order::getStatusName($order->getStatus()),
            ],
            'bartender'     => BartenderViewModel::full($order->getBartender()),
            'waiter'        => WaiterViewModel::full($order->getWaiter()),
            'details'       => OrderDetailViewModel::defaultList($order->getOrderDetails()),
            'status_log'    => OrderStatusLogViewModel::defaultList($order->getOrderStatuses()),
            'amount'        => $order->getAmount(),
            'created_at'    => self::date($order->getCreatedAt()),
            'updated_at'    => self::date($order->getUpdatedAt()),
            'deleted_at'    => self::date($order->getDeletedAt()),
        ];
    }
}