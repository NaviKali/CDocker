<?php

namespace app\middleware;

use tank\Seesion\Session;
use tank\Tool\Tool;

class sessionmiddleware
{
    /**
     * Session验证场景
     */
    public static function Handle(array $params)
    {
        $session = (new Session());

        $session->CreateSession("SessionStatus", session_status()); //*Session状态
        $session->CreateSession("SessionParams", $params); //*存储请求接口参数
        $session->CreateSession("SessionUserIP", Tool::GetIP()); //*当前用户IP地址
        $session->CreateSession("SessionUserNetWork",net_get_interfaces());//*当前用户网络信息

        session_encode();
        session_destroy();
    }
}
