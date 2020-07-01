<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 14:38
 */

namespace App\ViewModel;



use App\Database\OrderDetail\OrderDetail;
use Fcristiano\LaravelCommon\ViewModel\AbstractViewModel;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderDetailViewModel extends AbstractViewModel
{
    /**
     * @param $orderDetail
     * @return array
     */
    public static function default($orderDetail)
    {
        if(!$orderDetail instanceof OrderDetail) {
            return null;
        }

        return [
            'id'    => $orderDetail->getId(),
            'order' => [
                'id' => $orderDetail->getOrderId()
            ],
            'drink_id'  => $orderDetail->getDrinkExtId(),
            'price'     => $orderDetail->getPrice(),
            'created_at' => self::date($orderDetail->getCreatedAt()),
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