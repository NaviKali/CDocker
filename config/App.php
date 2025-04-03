<?php

#|----------------------------------|
#|       WECLOME TANK               |
#|     I LIKE PHP FROM THIS         |
#|      @email 2149573631@qq.com    |
#|----------------------------------|


/**
 * APP配置文件
 */
return [
    /**
     * 是否开启App场景化
     * @var bool
     */
    "IsStartApp" => true,
    /**
     * 是否开启严格验证[如果Base解码异常，抛出错误]
     * @var bool
     */
    "IsStartStrict" => false,
    /**
     * 代参
     * 200->通过
     * 404->所有接口无法访问
     * @var int
     */
    "AppCode" => 200,
    /**
     * Base64解码参数 | GET or POST
     * @var string
     */
    "AppParamsType" => "POST",
    /**
     * 不可调用类
     * @var array
     */
    "AppNotCallClass" => [],
];