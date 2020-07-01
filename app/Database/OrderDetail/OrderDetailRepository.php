<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 12:15
 */

namespace App\Database\OrderDetail;

use App\Database\Order\Order;
use Fcristiano\LaravelCommon\Services\Repository\AbstractRepository;


/**
 * Class BarTableRepository
 * @package App\Database\Bartender
 */
class OrderDetailRepository extends AbstractRepository
{

    /**
     * @param int $id
     * @return OrderDetail|null
     */
    public function getById($id): ?OrderDetail
    {
        $query = OrderDetail::query()
            ->where('id', '=', $id);

        return $this->getOneOrNull($query);
    }


    /**
     * @param Order $order
     * @return OrderDetail[]
     */
    public function getByOrder(Order $order): array
    {
        return OrderDetail::query()
            ->where('order_id', '=', $order->getId())
            ->orderBy('id', 'ASC')
            ->get();
    }
}