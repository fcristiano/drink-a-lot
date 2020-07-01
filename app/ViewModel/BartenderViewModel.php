<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 14:38
 */

namespace App\ViewModel;


use App\Database\Bartender\Bartender;
use Fcristiano\LaravelCommon\ViewModel\AbstractViewModel;

class BartenderViewModel extends AbstractViewModel
{
    /**
     * @param $bartender
     * @return array
     */
    public static function slim($bartender)
    {
        if(!$bartender instanceof Bartender) {
            return null;
        }

        return [
            'name' => $bartender->getName(),
        ];
    }

    /**
     * @param $bartender
     * @return array
     */
    public static function default($bartender)
    {
        if(!$bartender instanceof Bartender) {
            return null;
        }

        return [
            'id'            => $bartender->getId(),
            'name'          => $bartender->getName(),
            'surname'       => $bartender->getSurname(),
            'created_at'    => self::date($bartender->getCreatedAt()),
        ];
    }

    /**
     * @param $bartender
     * @return array
     */
    public static function full($bartender)
    {
        if(!$bartender instanceof Bartender) {
            return null;
        }

        return [
            'id'            => $bartender->getId(),
            'code'          => $bartender->getCode(),
            'name'          => $bartender->getName(),
            'surname'       => $bartender->getSurname(),
            'created_at'    => self::date($bartender->getCreatedAt()),
            'updated_at'    => self::date($bartender->getUpdatedAt()),
            'deleted_at'    => self::date($bartender->getDeletedAt()),
        ];
    }
}