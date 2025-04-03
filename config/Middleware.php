<?php

#|----------------------------------|
#|       WECLOME TANK               |
#|     I LIKE PHP FROM THIS         |
#|      @email 2149573631@qq.com    |
#|----------------------------------|


namespace config;

use config\Base\Base as Base;
use function tank\getRoot;
use tank\Request\Request;

/**
 * (配置)中间件
 */
$userMiddleware = [
        // "\app\middleware\middleware",
        // "\app\middleware\loginmiddleware",
        // "\app\middleware\\tokenmiddleware",
        // "\app\middleware\\routerunreal",
        // "\app\middleware\phpversionmiddleware",
        // "\app\middleware\localareanetworkrestrictionmiddleware",
        // "\app\middleware\useragentmiddleware",
        // "\app\middleware\sessionmiddleware",
        // "\app\middleware\uuidmiddleware",
        // "\app\middleware\localaccessmiddleware",
];

/**
 * 引入中间件
 */
//*获取路径名字
$Url = getRoot() . "app/middleware";
/**
 * 自动引入中间件
 */
Base::AutomaticInclude($Url);


/**
 * 循环遍历使用中间件
 */
foreach ($userMiddleware as $value) {
        $value::Handle(["GET"=>Request::param(),"POST"=>Request::postparam()],apache_request_headers(),apache_response_headers());
}
