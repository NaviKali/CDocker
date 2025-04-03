<?php
namespace app\middleware;


class loginmiddleware
{
        /**
         * 登录中间件
         * @access public
         * @static
         */
        public static function Handle()
        {
                /**
                 * 登录接口验证
                 */
                if (\tank\getCurrentUrl() == 'login' or \tank\getCurrentUrl() == "Login") {
                        $params = \tank\Request\Request::postparam(); //*获取POST请求参数
                        $isVer = \tank\BaseController::VerPassWordPreg($params['login_password']); //!这里的字段为 -> login_password
                        return $isVer ? null : \tank\Error("弱密码禁止登录!");
                }
        }

}