<?php

namespace app\admin\controller;

use tank\BaseController;
use tank\Request\Request;
use app\common\logic\command as Logiccommand;
use function tank\Success;


class Command extends BaseController
{
    /**
     * 构建一条命令
     * @access public
     * @api Command/builder
     * @author liulei
     * @return void
     */
    public function builder(Request $request): void
    {
        $params = $this->app->getAppParams();

        Success("命令成功！", [
            "return" => is_string(Logiccommand::start($params["command"])) ? str_replace("\n", "<br>", Logiccommand::start($params["command"])) : "ok"
        ]);
    }

}