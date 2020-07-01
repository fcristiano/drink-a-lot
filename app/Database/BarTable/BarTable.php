<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 11:22
 */

namespace App\Database\BarTable;


use App\Database\Order\Order;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BarTable
 * @package App\Database\BarTable
 *
 * @property-read   integer         id
 * @property        integer         number
 * @property        integer         default_seats
 * @property        Order[]         orders
 * @property        \DateTime       created_at
 * @property        \DateTime|null  updated_at
 */
class BarTable extends Model
{
    protected $modelName = self::class;

    protected $connection = 'mysql';

    protected $table = 'bar_tables';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'bar_table_id', 'id');
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
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return BarTable
     */
    public function setNumber(int $number): BarTable
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultSeats(): int
    {
        return $this->default_seats;
    }

    /**
     * @param int $default_seats
     * @return BarTable
     */
    public function setDefaultSeats(int $default_seats): BarTable
    {
        $this->default_seats = $default_seats;

        return $this;
    }

    /**
     * @return Order[]
     */
    public function getOrders(): array
    {
        return $this->orders;
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