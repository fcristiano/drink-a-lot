<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 12:15
 */

namespace App\Database\BarTable;

use Fcristiano\LaravelCommon\Services\Repository\AbstractRepository;


/**
 * Class BarTableRepository
 * @package App\Database\Bartender
 */
class BarTableRepository extends AbstractRepository
{

    /**
     * @param int $id
     * @return BarTable|null
     */
    public function getById($id): ?BarTable
    {
        $query = BarTable::query()
            ->where('id', '=', $id);

        return $this->getOneOrNull($query);
    }


    /**
     * @param int $tableNumber
     * @return BarTable|null
     */
    public function getByTableNumber(int $tableNumber): ?BarTable
    {
        $query = BarTable::query()
            ->where('number', '=', $tableNumber);

        return $this->getOneOrNull($query);
    }

}