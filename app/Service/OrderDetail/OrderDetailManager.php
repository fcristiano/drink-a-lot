<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 14:42
 */

namespace App\Service\OrderDetail;


use App\Database\OrderDetail\OrderDetail;

interface OrderDetailManager
{
    const DRINK_DEFAULT_PRICE = 8.50;

    /**
     * @param int $orderId
     * @param int $drinkId
     * @return OrderDetail
     * @throws \Exception
     */
    public function create(int $orderId, int $drinkId): OrderDetail;

    /**
     * @param $orderId
     * @return OrderDetail
     * @throws \Exception
     */
    public function delete($orderId): OrderDetail;
}