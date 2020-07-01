<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 11:22
 */

namespace App\Database\Bartender;


use App\Database\Order\Order;
use Fcristiano\LaravelCommon\Model\Utils\ManageDeletedAt;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bartender
 * @package App\Database\Bartender
 *
 * @property-read   integer         id
 * @property        string          code
 * @property        string          name
 * @property        string          surname
 * @property        Order[]         orders
 * @property        \DateTime       created_at
 * @property        \DateTime|null  updated_at
 * @property        \DateTime|null  deleted_at
 */
class Bartender extends Model
{
    use ManageDeletedAt;

    protected $modelName = self::class;

    protected $connection = 'mysql';

    protected $table = 'bartenders';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'bartender_id', 'id');
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Bartender
     */
    public function setCode(string $code): Bartender
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Bartender
     */
    public function setName(string $name): Bartender
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return Bartender
     */
    public function setSurname(string $surname): Bartender
    {
        $this->surname = $surname;

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