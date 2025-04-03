<?php

namespace app\void;

use tank\Env\env;
use tank\View\View;
use function tank\getAPPJSON;
use app\common\logic\menu as Logicmenu;
use app\common\logic\docker as Logicdocker;

class Page
{

    /**
     * 登录页面
     * 
     * @access public
     * @author liulei
     * @return void
     */
    public function Login(): void
    {
        View::Start("login", [], [
            "title" => getAPPJSON()->PROJECT_NAME . "|登录-" . (date("Y-m-d"))
        ]);
    }

    /**
     * 首页页面
     * 
     * @access public
     * @author liulei
     * @return void
     */
    public function Home(): void
    {
        View::Start("home", [
            "menus" => (new Logicmenu)->default,
            "dockerData" => (new Logicdocker([Logicdocker::ACTION_GETCONTAINERLIST,Logicdocker::ACTION_GETCONTAINERALLLIST,Logicdocker::ACTION_GETIMAGESLIST]))->data,
            "echart"=>[
                "data"=>join(",",(new Logicdocker(Logicdocker::ACTION_GETCONTAINERALLLIST))->getContainerListTypeAndCount()["type"]),
                "count"=>join(",",(new Logicdocker(Logicdocker::ACTION_GETCONTAINERALLLIST))->getContainerListTypeAndCount()["count"]),
            ],
        ], [
            "title" => getAPPJSON()->PROJECT_NAME . "|首页",
        ]);
    }
}