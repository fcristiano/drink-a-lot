<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 14:38
 */

namespace App\ViewModel;


use App\Database\Waiter\Waiter;
use Fcristiano\LaravelCommon\ViewModel\AbstractViewModel;

class WaiterViewModel extends AbstractViewModel
{
    /**
     * @param $waiter
     * @return array
     */
    public static function slim($waiter)
    {
        if(!$waiter instanceof Waiter) {
            return null;
        }

        return [
            'name' => $waiter->getName(),
        ];
    }

    /**
     * @param $waiter
     * @return array
     */
    public static function default($waiter)
    {
        if(!$waiter instanceof Waiter) {
            return null;
        }

        return [
            'id'            => $waiter->getId(),
            'name'          => $waiter->getName(),
            'surname'       => $waiter->getSurname(),
            'created_at'    => self::date($waiter->getCreatedAt()),
        ];
    }

    /**
     * @param $waiter
     * @return array
     */
    public static function full($waiter)
    {
        if(!$waiter instanceof Waiter) {
            return null;
        }

        return [
            'id'            => $waiter->getId(),
            'code'          => $waiter->getCode(),
            'name'          => $waiter->getName(),
            'surname'       => $waiter->getSurname(),
            'created_at'    => self::date($waiter->getCreatedAt()),
            'updated_at'    => self::date($waiter->getUpdatedAt()),
            'deleted_at'    => self::date($waiter->getDeletedAt()),
        ];
    }
}