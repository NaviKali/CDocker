<?php

#|----------------------------------|
#|       WECLOME TANK               |
#|     I LIKE PHP FROM THIS         |
#|      @email 2149573631@qq.com    |
#|----------------------------------|

namespace config;

/**
 * 本地管理员
 * TODO 有些时候管理员的账号和密码不一定要出现在数据库里面，这一点是为了防止SQL注入。
 */
return [
    /**
     * 是否开启Base64编码模式
     */
    "Base64" => true,
    /**
     * 本地管理员数量最大长度
     */
    "LocalhostAdminMaxLimit" => 2,
];