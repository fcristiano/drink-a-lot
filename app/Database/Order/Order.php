<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 11:22
 */

namespace App\Database\Order;


use App\Database\BarTable\BarTable;
use App\Database\Bartender\Bartender;
use App\Database\OrderDetail\OrderDetail;
use App\Database\OrderStatusLog\OrderStatusLog;
use App\Database\Waiter\Waiter;
use Fcristiano\LaravelCommon\Model\Utils\ManageDeletedAt;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * @package App\Database\Order
 *
 * @property-read   integer             id
 * @property        integer             bar_table_id
 * @property        BarTable            bar_table
 * @property        integer|null        bartender_id
 * @property        Bartender|null      bartender
 * @property        integer|null        waiter_id
 * @property        Bartender|null      waiter
 * @property        integer             status
 * @property        OrderDetail[]       order_details
 * @property        OrderStatusLog[]    order_statuses
 * @property        float               amount
 * @property        \DateTime           created_at
 * @property        \DateTime|null      updated_at
 * @property        \DateTime|null      deleted_at
 */
class Order extends Model
{
    use ManageDeletedAt;

    protected $modelName = self::class;

    protected $connection = 'mysql';

    protected $table = 'orders';


    const STATUS_CUSTOMER_COMPILING = 10;
    const STATUS_READY_TO_BE_MADE = 15;
    const STATUS_BARTENDER_MAKING = 20;
    const STATUS_READY_TO_BE_DELIVERED = 25;
    const STATUS_WAITER_DELIVERING = 30;
    const STATUS_DELIVERED = 35;
    const STATUS_PAYED = 40;
    const STATUS_DELETED = 95;
    const STATUS_DISCARDED = 99;

    protected static $validStatuses = [
        self::STATUS_CUSTOMER_COMPILING,
        self::STATUS_READY_TO_BE_MADE,
        self::STATUS_BARTENDER_MAKING,
        self::STATUS_READY_TO_BE_DELIVERED,
        self::STATUS_WAITER_DELIVERING,
        self::STATUS_DELIVERED,
        self::STATUS_PAYED,
        self::STATUS_DELETED,
        self::STATUS_DISCARDED,
    ];

    protected static $statusName = [
        self::STATUS_CUSTOMER_COMPILING       => 'customer_compiling',
        self::STATUS_READY_TO_BE_MADE         => 'ready_to_be_made',
        self::STATUS_BARTENDER_MAKING         => 'bartender_making',
        self::STATUS_READY_TO_BE_DELIVERED    => 'ready_to_be_delivered',
        self::STATUS_WAITER_DELIVERING        => 'waiter_delivering',
        self::STATUS_DELIVERED                => 'delivered',
        self::STATUS_PAYED                    => 'payed',
        self::STATUS_DELETED                  => 'deleted',
        self::STATUS_DISCARDED                => 'discarded',
    ];

    /**
     * @param $status
     * @return bool
     */
    public static function isStatusValid($status): bool
    {
        return in_array($status, self::$validStatuses);
    }

    /**
     * @param $status
     * @return string
     */
    public static function getStatusName($status): string
    {
        if(!self::isStatusValid($status)) {
            return null;
        }

        return self::$statusName[$status];
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bar_table()
    {
        return $this->hasOne(BarTable::class, 'id', 'bar_table_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bartender()
    {
        return $this->hasOne(Bartender::class, 'id', 'bartender_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function waiter()
    {
        return $this->hasOne(Waiter::class, 'id', 'waiter_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_statuses()
    {
        return $this->hasMany(OrderStatusLog::class, 'order_id', 'id');
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
    public function getBarTableId(): int
    {
        return $this->bar_table_id;
    }

    /**
     * @param int $bar_table_id
     * @return Order
     */
    public function setBarTableId(int $bar_table_id): Order
    {
        $this->bar_table_id = $bar_table_id;

        return $this;
    }

    /**
     * @return BarTable
     */
    public function getBarTable(): BarTable
    {
        return $this->bar_table;
    }

    /**
     * @return int|null
     */
    public function getBartenderId(): ?int
    {
        return $this->bartender_id;
    }

    /**
     * @param int $bartender_id
     * @return Order
     */
    public function setBartenderId(int $bartender_id): Order
    {
        $this->bartender_id = $bartender_id;

        return $this;
    }

    /**
     * @return Bartender|null
     */
    public function getBartender(): ?Bartender
    {
        return $this->bartender;
    }

    /**
     * @return int|null
     */
    public function getWaiterId(): ?int
    {
        return $this->waiter_id;
    }

    /**
     * @param int $waiter_id
     * @return Order
     */
    public function setWaiterId(int $waiter_id): Order
    {
        $this->waiter_id = $waiter_id;

        return $this;
    }

    /**
     * @return Bartender|null
     */
    public function getWaiter(): ?Waiter
    {
        return $this->waiter;
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
     * @return Order
     * @throws \Exception
     */
    public function setStatus(int $status): Order
    {
        if(!self::isStatusValid($status)) {
            throw new \Exception(sprintf('Order status "%d" is not valid', $status));
        }

        $this->status = $status;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Order
     */
    public function setAmount(float $amount): Order
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return OrderStatusLog[]
     */
    public function getOrderDetails(): array
    {
        return $this->order_details()->orderBy('id', 'ASC')->get()->all();
    }

    /**
     * @return OrderStatusLog[]
     */
    public function getOrderStatuses(): array
    {
        return $this->order_statuses()->orderBy('id', 'ASC')->get()->all();
    }

    /**
     * @return OrderStatusLog|null
     */
    public function getLastOrderStatus(): ?OrderStatusLog
    {
        return $this->order_statuses()->orderBy('id', 'DESC')->limit(1)->get()->first();
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