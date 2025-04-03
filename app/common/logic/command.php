<?php

namespace app\common\logic;

class command
{
    /**
     * 开启一条命令
     * 
     * @access public
     * @static
     * @author liulei
     * @return bool|string|null
     */
    public static function start($command = ""): bool|string|null
    {
        return shell_exec($command);
    }
}