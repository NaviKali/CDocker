<?php
/**
 * HTTP错误异常
 */
namespace tank\Error;

use tank\Error\error;

class phpversionError extends \Exception
{
    public function __construct(float|int|string $version = "8.0.2")
    {
            error::create("PHP版本过低![请安装>= $version 版本的PHP][当前PHP版本:".PHP_VERSION."]", __FILE__, __LINE__);
    }

}