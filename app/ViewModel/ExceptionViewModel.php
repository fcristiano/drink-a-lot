<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 02:49
 */

namespace App\ViewModel;


use Fcristiano\LaravelCommon\ViewModel\AbstractViewModel;

class ExceptionViewModel extends AbstractViewModel
{
    /**
     * @param \Throwable $e
     * @return array
     */
    public static function slim(\Throwable $e)
    {
        return [
            'message' => $e->getMessage()
        ];
    }

    /**
     * @param \Throwable $e
     * @return array
     */
    public static function default(\Throwable $e)
    {
        return [
            'message'   => $e->getMessage(),
            'code'      => $e->getCode(),
            'file'      => $e->getFile(),
            'line'      => $e->getLine(),
            'trace'     => $e->getTrace(),
        ];
    }
}