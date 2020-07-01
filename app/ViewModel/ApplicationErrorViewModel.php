<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 02:49
 */

namespace App\ViewModel;


use Fcristiano\LaravelCommon\ViewModel\AbstractViewModel;

class ApplicationErrorViewModel extends AbstractViewModel
{
    /**
     * @param string $message
     * @return array
     */
    public static function default(string $message)
    {
        return [
            'message' => $message
        ];
    }
}