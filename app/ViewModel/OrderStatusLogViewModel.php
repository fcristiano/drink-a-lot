<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 14:38
 */

namespace App\ViewModel;



use App\Database\Order\Order;
use App\Database\OrderStatusLog\OrderStatusLog;
use Fcristiano\LaravelCommon\ViewModel\AbstractViewModel;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderStatusLogViewModel extends AbstractViewModel
{
    /**
     * @param $orderStatusLog
     * @return array
     */
    public static function default($orderStatusLog)
    {
        if(!$orderStatusLog instanceof OrderStatusLog) {
            return null;
        }

        return [
            'id'    => $orderStatusLog->getId(),
            'order' => [
                'id' => $orderStatusLog->getOrderId()
            ],
            'status' => [
                'value' => $orderStatusLog->getStatus(),
                'name'  => Order::getStatusName($orderStatusLog->getStatus()),
            ],
            'created_at' => self::date($orderStatusLog->getCreatedAt()),
        ];
    }

    /**
     * @param $list
     * @return array
     */
    public static function defaultList($list)
    {
        $result = [];
        foreach($list as $item) {
            $result []= self::default($item);
        }

        if($list instanceof LengthAwarePaginator) {
            $pagination = self::paginator($list);

            return [
                'pagination' => $pagination,
                'items'      => $result
            ];
        }

        return $result;
    }
}