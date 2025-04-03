<?php

namespace app\common\logic;

use app\common\logic\command as Logiccommand;

class systemctl
{
    const SYSTEMCTL_WINDOWS = "Windows";
    const SYSTEMCTL_WINDOWS_32 = "Windows32";
    const SYSTEMCTL_WINDOWS_64 = "Windows64";
    const SYSTEMCTL_LINUX = "Linux";
    const SYSTEMCTL_LINUX_32 = "Linux32";
    const SYSTEMCTL_LINUX_64 = "Linux64";

    /**
     * 获取我的系统
     * 
     * @access public
     * @return bool|string|null
     */
    public function getMySystemctl(): bool|string|null{
        return Logiccommand::start("uname");
    }
}