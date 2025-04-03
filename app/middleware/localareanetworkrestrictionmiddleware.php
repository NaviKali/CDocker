<?php

namespace app\middleware;


use tank\Error\httpError;
use tank\Tool\Tool;
use function tank\getCurrentFunctionName;
use function tank\getConfigUrl;

class localareanetworkrestrictionmiddleware
{
    /**
     * 局域网ip用户限制执行
     * @access public
     * @static
     * @throws httpError
     * @return mixed
     */
    public static function Handle(): mixed
    {
        /**
         * *这里配置个别局域网ip下的用户能使用的函数名
         * *例如:192.168.1.1 => ["index",...]
         */

        $there = require_once getConfigUrl() . "/Localareanetworkrestriction.php";
        $ip = Tool::GetIP();
        if (in_array($ip, array_keys($there))) {
            if (!in_array(getCurrentFunctionName(), $there[$ip]))
                throw new httpError("当前IP无法调用该函数!");
        }
        return null;
    }
}
