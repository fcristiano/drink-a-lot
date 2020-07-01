<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 12:15
 */

namespace App\Database\Bartender;

use App\Database\Order\Order;
use Fcristiano\LaravelCommon\Services\Repository\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

/**
 * Class BartenderRepository
 * @package App\Database\Bartender
 */
class BartenderRepository extends AbstractRepository
{

    /**
     * @param int $id
     * @return Bartender|null
     */
    public function getById($id)
    {
        $query = Bartender::query()
            ->where('id', '=', $id);

        return $this->getOneOrNull($query);
    }

    /**
     * @return Bartender|null
     */
    public function getFirstAvailable()
    {
        $query = Bartender::query()
            ->select('bartenders.*')
            ->leftJoin('orders', 'bartenders.id', '=', 'orders.bartender_id')
            ->where(function(Builder $query) {
                $query
                    ->where('orders.status', '!=', Order::STATUS_BARTENDER_MAKING)
                    ->orWhere('orders.status', '=', null);
            })
            ->where('bartenders.deleted_at', '=', null)
            ->limit(1);

        return $this->getOneOrNull($query);
    }

}