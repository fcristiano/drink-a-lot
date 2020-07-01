<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 11:22
 */

namespace App\Database\OrderDetail;


use App\Database\Order\Order;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderDetail
 * @package App\Database\OrderDetail
 *
 * @property-read   integer         id
 * @property        integer         order_id
 * @property        Order           order
 * @property        integer         drink_ext_id
 * @property        float           price
 * @property        \DateTime       created_at
 * @property        \DateTime|null  updated_at
 */
class OrderDetail extends Model
{
    protected $modelName = self::class;

    protected $connection = 'mysql';

    protected $table = 'order_details';


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
     * @return OrderDetail
     */
    public function setOrderId(int $order_id): OrderDetail
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
    public function getDrinkExtId(): int
    {
        return $this->drink_ext_id;
    }

    /**
     * @param int $drink_ext_id
     * @return OrderDetail
     */
    public function setDrinkExtId(int $drink_ext_id): OrderDetail
    {
        $this->drink_ext_id = $drink_ext_id;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return OrderDetail
     */
    public function setPrice(float $price): OrderDetail
    {
        $this->price = $price;

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