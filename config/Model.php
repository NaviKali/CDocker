<?php

#|----------------------------------|
#|       WECLOME TANK               |
#|     I LIKE PHP FROM THIS         |
#|      @email 2149573631@qq.com    |
#|----------------------------------|


namespace config;

use config\Base\Base as Base;

use function tank\getRoot;

/**
 * (配置)Model模型层
 */

//*获取路径名字
$ModelUrl = getRoot() . "app/model";

/**
 * Model模型层引入函数
 */
Base::AutomaticInclude($ModelUrl);
