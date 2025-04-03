<?php

namespace app\middleware;

use tank\Error\phpversionError;


class phpversionmiddleware
{
    /**
     * PHP版本验证
     * @static
     * @throws phpversionError
     * @return mixed
     */
    public static function Handle()
    {
        if ((int)PHP_VERSION < 8) {
            throw new phpversionError();
        } else {
            return null;
        }
    }
}