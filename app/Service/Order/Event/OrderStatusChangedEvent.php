<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 10:45
 */

namespace App\Service\Order\Event;


use App\Database\Order\Order;

class OrderStatusChangedEvent
{
    /** @var Order */
    protected $order;

    /** @var int */
    protected $orderStatusOld;

    /**
     * MessageReadEvent constructor.
     * @param Order $order
     * @param int $orderStatusOld
     * @throws \Exception
     */
    public function __construct(Order $order, int $orderStatusOld)
    {
        $this->order = $order;
        $this->orderStatusOld = $orderStatusOld;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @return int
     */
    public function getOrderStatusOld(): int
    {
        return $this->orderStatusOld;
    }

}