<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 12:15
 */

namespace App\Database\OrderStatusLog;

use Fcristiano\LaravelCommon\Services\Repository\AbstractRepository;


/**
 * Class BarTableRepository
 * @package App\Database\Bartender
 */
class OrderStatusLogRepository extends AbstractRepository
{

    /**
     * @param int $id
     * @return OrderStatusLog|null
     */
    public function getById($id)
    {
        $query = OrderStatusLog::query()
            ->where('id', '=', $id);

        return $this->getOneOrNull($query);
    }

}