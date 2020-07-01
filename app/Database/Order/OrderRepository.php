<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 12:15
 */

namespace App\Database\Order;

use App\Database\BarTable\BarTable;
use Fcristiano\LaravelCommon\Services\Repository\AbstractRepository;


/**
 * Class BarTableRepository
 * @package App\Database\Bartender
 */
class OrderRepository extends AbstractRepository
{

    /**
     * @param int $id
     * @return Order|null
     */
    public function getById($id)
    {
        $query = Order::query()
            ->where('id', '=', $id);

        return $this->getOneOrNull($query);
    }

    /**
     * @param BarTable $barTable
     * @return Order|null
     */
    public function getCustomerCompilingByTable(BarTable $barTable)
    {
        $query = Order::query()
            ->where('bar_table_id', '=', $barTable->getId())
            ->where('status', '=', Order::STATUS_CUSTOMER_COMPILING);

        return $this->getOneOrNull($query);
    }

    /**
     * @return Order[]
     */
    public function getProcessableList()
    {
        $query = Order::query()
            ->where('status', '!=', Order::STATUS_CUSTOMER_COMPILING)
            ->where('status', '!=', Order::STATUS_PAYED)
            ->where('status', '!=', Order::STATUS_DELETED)
            ->where('status', '!=', Order::STATUS_DISCARDED);

        return $query->get()->all();
    }
}