<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 12:15
 */

namespace App\Database\Waiter;

use App\Database\Order\Order;
use Fcristiano\LaravelCommon\Services\Repository\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WaiterRepository
 * @package App\Database\Waiter
 */
class WaiterRepository extends AbstractRepository
{

    /**
     * @param int $id
     * @return Waiter|null
     */
    public function getById($id)
    {
        $query = Waiter::query()
            ->where('id', '=', $id);

        return $this->getOneOrNull($query);
    }

    /**
     * @return Waiter|null
     */
    public function getFirstAvailable()
    {
        $query = Waiter::query()
            ->select('waiters.*')
            ->leftJoin('orders', 'waiters.id', '=', 'orders.waiter_id')
            ->where(function(Builder $query) {
                $query
                    ->where('orders.status', '!=', Order::STATUS_BARTENDER_MAKING)
                    ->orWhere('orders.status', '=', null);
            })
            ->where('waiters.deleted_at', '=', null)
            ->limit(1);

        return $this->getOneOrNull($query);
    }
}