<?php
/**
 * HTTP错误异常
 */
namespace tank\Error;

use tank\Error\error;

class httpError extends \Exception
{
    /**
     * 创建一个HTTP错误异常
     */
    public function __construct(string $message = "", int $code = 500, \Throwable $previous = null)
    {
        if ($code == 500)
            error::create($message, __FILE__, __LINE__);

        die;
    }

}