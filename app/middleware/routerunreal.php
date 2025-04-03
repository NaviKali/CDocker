<?php

namespace app\middleware;

use tank\Request\Request;
use tank\Web\http;

class routerunreal
{
    /**
     * 路由虚幻
     * @access public
     * @static
     */
    public static function Handle()
    {
        $params = Request::param();
        if (empty($params["isStartUnreal"])) {
            header("Location:" . http::RouterUnreal());
        }
    }

}