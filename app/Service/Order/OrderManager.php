<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 14:42
 */

namespace App\Service\Order;


use App\Database\Order\Order;

interface OrderManager
{
    /**
     * @param int $tableNumber
     * @return Order
     * @throws \Exception
     */
    public function create(int $tableNumber): Order;

    /**
     * @param int $orderId
     * @return Order
     * @throws \Exception
     */
    public function confirmedByCustomer(int $orderId): Order;

    /**
     * @param int $orderId
     * @param int $bartenderId
     * @param bool $forceAssign
     * @return Order
     * @throws \Exception
     */
    public function takenChargeByBartender(int $orderId, int $bartenderId, $forceAssign = false): Order;

    /**
     * @param int $orderId
     * @param int $waiterId
     * @param bool $forceAssign
     * @return Order
     * @throws \Exception
     */
    public function takenChargeByWaiter(int $orderId, int $waiterId, $forceAssign = false): Order;

    /**
     * @param int $orderId
     * @return Order
     * @throws \Exception
     */
    public function completedByBartender(int $orderId): Order;

    /**
     * @param int $orderId
     * @return Order
     * @throws \Exception
     */
    public function completedByWaiter(int $orderId): Order;

    /**
     * @param int $orderId
     * @param int $newStatus
     * @return Order
     * @throws \Exception
     */
    public function changeStatus(int $orderId, int $newStatus): Order;

    /**
     * @param $orderId
     * @param $orderStatusOld
     * @return Order
     * @throws \Exception
     */
    public function manageOrderStatusChanged($orderId, $orderStatusOld): Order;

    /**
     * @param $orderId
     * @return Order
     * @throws \Exception
     */
    public function delete($orderId): Order;
}