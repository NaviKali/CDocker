<?php

namespace app\admin\controller;

use tank\BaseController;
use tank\Env\env;
use tank\Func\Func;
use tank\Param\Param;
use tank\Request\Request;
use tank\Seesion\Session;
use tank\Tool\Tool;
use function tank\Error;
use function tank\Success;


class Login extends BaseController
{
    const TYPE_PASSWORD = "PASSWORD";
    const TYPE_MDDATE = "MD5DATE";
    const TYPE_NONE = "NONE";
    /**
     * 验证类型
     */
    public array $verTypeList = [
        "PASSWORD",
        "MD5DATE",
        "NONE",
    ];

    /**
     * 登录
     * 
     * @access public
     * @api Login/Login
     * @param Request $request 
     * @return void
     */
    public function Login(Request $request): void
    {
        Func::SingleVerCallFunction("POST", __FUNCTION__, function (array $params) {
            //*获取登录类型
            $LoginType = (new env())->get("LOGIN_TYPE");

            $params = $this->app->getAppParams();

            switch ($LoginType) {
                case self::TYPE_PASSWORD:
                    if ($params["data"] != (new env())->get("LOGIN_PASSWORD")) {
                        Error("密码错误!");
                    }
                    break;
                case self::TYPE_MDDATE:
                    if ($params["data"] != md5(date("Y-m-d"))) {
                        Error("加密错误!");
                    }
                    break;
                case self::TYPE_NONE:
                    break;
                default: {
                    Error("没有该登录类型!");
                }
            }
            Success("登录成功!", [
                "uuid" => uniqid()
            ]);
        }, [
            "data|登录数据|require" => "None"
        ]);
    }


}