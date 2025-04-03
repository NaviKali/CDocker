<?php

/**
 * HTTP错误异常
 */

namespace tank\Error;

use tank\Error\error;

class uploadfiletypeError extends \Exception
{
    public function __construct()
    {
        error::create("文件上传类型错误!", __FILE__, __LINE__);
    }
}
