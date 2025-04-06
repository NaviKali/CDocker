<?php

namespace app\admin\controller;

use Error;
use tank\BaseController;
use app\common\logic\docker as Logicdocker;
use tank\Request\Request;
use function tank\Success;
use function tank\Error;

class Docker extends BaseController
{
    /**
     * 关闭容器
     * @access public
     * @api Docker/closeContainer
     * @param Request $request must
     * @return void
     */
    public function closeContainer(Request $request): void
    {
        $params = $this->app->getAppParams();

        (new Logicdocker())->closeContainer($params["container"]);

        Success("关闭成功!");
    }
    /**
     * 重启容器
     * @access public
     * @api Docker/restartContainer
     * @param Request $request
     * @return void
     */
    public function restartContainer(Request $request): void
    {
        $params = $this->app->getAppParams();

        (new Logicdocker())->restartContainer($params["container"]);

        Success("重启成功!");
    }
    /**
     * 删除镜像
     * @access public
     * @api Docker/deleteImage
     * @param Request $request
     * @return void
     */
    public function deleteImage(Request $request): void
    {
        $params = $this->app->getAppParams();
        $return = (new Logicdocker())->deleteImage($params["image"]);
        if (!$return)
            Error("需要删除使用于该镜像的容器!");
        Success($return);
    }
}