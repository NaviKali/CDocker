<?php

namespace app\middleware;

use tank\Error\httpError;
use tank\Request\Request;

use function tank\getConfigUrl;

class useragentmiddleware
{
    /**
     * 浏览器用户代理验证
     * @throws httpError
     */
    public static function Handle()
    {
        $config = require_once (getConfigUrl() . "/RequestHeader.php");
        $user_agent = Request::headers("User-Agent");

        if ($config["isStopEdge"] and str_contains($user_agent, "Edg"))
            throw new httpError("Edge浏览器不支持使用!");

    }
}