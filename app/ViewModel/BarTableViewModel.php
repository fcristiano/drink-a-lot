<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 14:38
 */

namespace App\ViewModel;


use App\Database\BarTable\BarTable;
use Fcristiano\LaravelCommon\ViewModel\AbstractViewModel;

class BarTableViewModel extends AbstractViewModel
{
    /**
     * @param $barTable
     * @return array
     */
    public static function slim($barTable)
    {
        if(!$barTable instanceof BarTable) {
            return null;
        }

        return [
            'id'            => $barTable->getId(),
            'number'        => $barTable->getNumber(),
            'default_seats' => $barTable->getDefaultSeats(),
        ];
    }

    /**
     * @param $barTable
     * @return array
     */
    public static function default($barTable)
    {
        if(!$barTable instanceof BarTable) {
            return null;
        }

        return [
            'id'            => $barTable->getId(),
            'number'        => $barTable->getNumber(),
            'default_seats' => $barTable->getDefaultSeats(),
            'created_at'    => self::date($barTable->created_at),
            'updated_at'    => self::date($barTable->updated_at),
        ];
    }
}