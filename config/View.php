<?php

#|----------------------------------|
#|       WECLOME TANK               |
#|     I LIKE PHP FROM THIS         |
#|      @email 2149573631@qq.com    |
#|----------------------------------|


/**
 * 视图层配置
 */
return [
    /**
     * 静态文件路径
     * TODO快速引入本地文件。
     * !请采取用符号的形式来定义路径。
     */
    "StaticFileUrl" => [
        "JSURL" => "http://localhost:8080/public/static/js",
        "CSSURL" => "http://localhost:8080/public/static/css",
        "IMAGEURL" => "http://localhost:8080/public/static/images"
    ],
    /**
     * 自定义标签
     * TODO创建属于自己的标签
     * !推荐采取格式->标签头:标签| . 标签尾:|标签
     */
    "CustomLabel" => [
        "[includeNavbar]"=>'$(self::Start("component/navbar",$TK,[]);)$',
        /**
         * 路由标签
         */
        "[ResetRoute]" => "$(t-var('routeto',",
        "[/ResetRoute]" => "))$",
        "[Route]" => '<a href="$(t-get("routeto"))$">',
        "[/Route]" => '</a>',
        /**
         * 闭包标签
         */
        /**
         * (function(){
         * 
         * })()
         */
        "[Clouse]" => "(function",
        "[ClouseParam]" => "(",
        "[/ClouseParam]" => ")",
        "[ClouseUse]" => "use(",
        "[/ClouseUse]" => ")",
        "[ClouseBody]" => "{",
        "[/ClouseBody]" => "}",
        "[/Clouse]" => ")",
        "[ClouseSetParam]" => "(",
        "[/ClouseSetParam]" => ")",
    ],
];