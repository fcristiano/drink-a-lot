<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 11:22
 */

namespace App\Database\OrderStatusLog;


use App\Database\Order\Order;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderStatusLog
 * @package App\Database\OrderStatusLog
 *
 * @property-read   integer         id
 * @property        integer         order_id
 * @property        Order           order
 * @property        integer         status
 * @property        \DateTime       created_at
 * @property        \DateTime|null  updated_at
 */
class OrderStatusLog extends Model
{
    protected $modelName = self::class;

    protected $connection = 'mysql';

    protected $table = 'order_status_log';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->order_id;
    }

    /**
     * @param int $order_id
     * @return OrderStatusLog
     */
    public function setOrderId(int $order_id): OrderStatusLog
    {
        $this->order_id = $order_id;

        return $this;
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
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return OrderStatusLog
     */
    public function setStatus(int $status): OrderStatusLog
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

}