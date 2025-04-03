<?php

namespace tank\Func;

use tank\MG\MG;
use tank\Request\Request;
use tank\Tool\Tool as Tool;
use app\model\Token as ModelToken;
use tank\App\App;
use function tank\{Error, Success};
use tank\BaseController;
use tank\MG\Operate;

/**
 * 路由参数调用方法函数。
 * TODO路由参数来调用PHP指定函数。
 */
/* 定义调用函数类型 -> 请求类型 */
/**
 * *除了GET和POST的类型以为，这里还定义了多种其余类型的接口请求类型。
 */
define("GET", 1); //todo 用来获取列表或详情页之类的接口类型
define("POST", 2); //todo 用来进行需要隐藏参数传值的操作的接口类型
define("DELETE", 3); //todo 用来进行删除某个单条数据的接口类型 (from GET)
define("LOGIN", 4); //todo 用来验证用户登录身份的接口类型(from POST)
class Func
{

        /**
         * (GET传参)Base64格式解码
         * TODO用来防止其余人员进行管理操作的时候直接传入中文或其余的正常参数。
         * @author LL
         * @access public
         */
        public static function BaseDeCodeUrl()
        {
                $params = $_SERVER['QUERY_STRING'] ?? [];
                if (empty($params)) {
                        return [];
                }
                $params = explode("&", $params);
                $con = [];
                for ($i = 0; $i < count($params); $i++) {
                        $params[$i] = explode('=', $params[$i]);
                        $params[$i][1] = base64_decode($params[$i][1]);
                        $con[$params[$i][0]] = $params[$i][1];
                }
                return $con;
        }

        /**
         * 获取路由
         * TODO用来查看路径地址。
         * @static
         * @return string
         */
        public static function getUrl(): string
        {
                return $_SERVER['REQUEST_URI'];
        }
        /**
         * 自动调用函数->自动根据路由进行回调函数
         * TODO用来自动验证回调函数，减少时间成本！
         * @static
         * @param mixed $Class 可以是控制层的类也可以是函数层(void)类 必填
         * @return void
         */
        public static function AutoVerCallFunction(mixed $Class): void
        {
                //*获取路径
                $url = $_SERVER['REQUEST_URI'];
                $url = str_replace("\\", "", $url); //反斜杆
                $urlArr = explode('/', $url); //!转换数组
                $MainUrl = $urlArr[count($urlArr) - 1];
                //?判断是否存在GET请求携带的参数 -> ?
                if (str_contains($url, "?")) {
                        $MainUrlArr = explode("?", $MainUrl);
                        $MainUrl = $MainUrlArr[0];
                }
                //*实例化并且回调
                $Call = new $Class();
                $Call->$MainUrl();
        }
        /**
         * 单条路由验证调用函数器->接口访问
         * TODO检查路由最后一个字符是否匹配函数名 -> 匹配则回调该函数。
         * @static
         * @param string $type 验证类型[GET or POST]
         * @param string $functionName 函数名 必填
         * @param \Closure $fucntion 回调函数 必填
         * @param array $params 参数 选填 ↓ 
         * [
         * LOGIN接口类型：填写登录字段(数组)
         * ]
         * !根据接口类型给予对应的参数
         * @return mixed
         */
        public static function SingleVerCallFunction(string $type, string $functionName, \Closure $function, array $params = [])
        {
                //?判断参数类型
                $type = match ($type) {
                        "GET" => GET,
                        "POST" => POST,
                        "DELETE" => DELETE,
                        "LOGIN" => LOGIN,
                };
                //*获取路径
                $url = $_SERVER['REQUEST_URI'];
                $url = str_replace("\\", "", $url); //反斜杆
                $urlArr = explode('/', $url); //!转换数组
                //*获取总长度
                $Length = count($urlArr);
                try {
                        switch ($type) {
                                /* GET类型 */
                                case GET:
                                        if (!str_contains($urlArr[$Length - 1], $functionName))
                                                return false;
                                        //!回调函数
                                        self::BathVerParams(GET, $params);
                                        //*获取GET请求参数
                                        $getParams = Request::param();
                                        return $function($getParams);
                                /* POST类型 */
                                case POST:
                                        if (!str_contains($urlArr[$Length - 1], $functionName))
                                                return false;
                                        //!回调函数
                                        self::BathVerParams(POST, $params);
                                        //*获取POST请求参数
                                        $postParams = Request::postparam();
                                        return $function($postParams);
                                /* DELETE类型 */
                                case DELETE:
                                        if (!str_contains($urlArr[$Length - 2], $functionName))
                                                return false;
                                        //!这里的DELETE接口类型是拒绝参数访问的(避免不必要的错误出现。)
                                        //*DELETE类型会返回回调函数一个参数，这个参数可以是id/guid/uuid等等，主要获取某一个单条数据主要筛选条件。
                                        return $function($urlArr[$Length - 1]);
                                /* LOGIN类型 */
                                case LOGIN:
                                        //*LOGIN接口类型提供了是否给予Token值(boolean)。
                                        $isGetToken = end($params); //?判断是否要求获取Token
                                        array_pop($params); //*删除
                                        //!LOGIN接口类型属于验证型判断
                                        self::VerLOGINTypes(loginField: $params, isGetToken: $isGetToken);
                                        return $function();
                        }
                } catch (\Exception $e) {
                        return $e;
                }
        }

        /**
         * 验证登录接口类型
         * TODOLOGIN请求接口类型 -> 用来给LOGIN接口类型进行验证查询。
         * @access private
         * @author LL
         * @param array $loginField 登录字段 必填
         * @param bool $isGetToken 是否获取Token 必填
         * @param string $model 模型层 选填
         * @return void 成功或失败 回调
         */
        private static function VerLOGINTypes(array $loginField, bool $isGetToken, string $model = 'login')
        {
                $param = Request::postparam();
                $con = [];
                for ($i = 0; $i < count($loginField); $i++) {
                        $con[$loginField[$i]] = $param[$loginField[$i]];
                }

                //?验证账号密码是否正确
                $findAccount = (new MG("login"))->where($con)->Once();
                if (!$findAccount)
                        return Error("账号密码错误！");

                //?验证Token是否存在
                $findToken = (new MG("token"))->where(["by_guid" => $findAccount["login_guid"]])->Once();
                if (!$findToken) {
                        $exportTime = new \DateTime(); // 获取当前时间
                        $exportTime->modify('+2 day'); // 添加一天
                        $exportTime = $exportTime->format('y.m.d-H.i:s'); // 转换为时间格式
                        (new ModelToken())->Modelcreate([Operate::MakeToken(), $findAccount["login_guid"], 1, $exportTime]);
                }

                //?如果Token存在
                //!刷掉Token，即异地登录 | 冲突登录
                (new MG('token'))->where(["by_guid" => $findAccount["login_guid"]])->update([
                        'token_value' => Operate::MakeToken()
                ]);

                //?判断是否要求获取Token
                $token = $isGetToken ? (new MG("token"))->where(["by_guid" => $findAccount["login_guid"]])->Once()['token_value'] : null;
                // 查找单条数据 
                $find = (new MG($model))->where($con)->Once();
                $find["token"] = $token; //*给予Token  值/null
                if (!$find)
                        return Error("登录失败!");
                /** 
                 * 存储登录信息
                 */
                $_SESSION["Login"] = $find;
                return Success("登录成功!", $find);
        }

        /**
         * 批量参数验证器
         * @static
         * TODO用来批量验证前端请求接口携带参数
         * @param int $type 请求类型 必填
         * @param array $params 参数 必填
         * @test 参数写法格式: 参数名|中文名|是否必填(require|unrequire)
         */
        final public static function BathVerParams(int $type, array $params)
        {
                //*获取Key键
                $KeysParams = array_keys($params);
                //*获取验证规则
                $ValueParams = array_values($params);
                //*GET请求
                if ($type == GET) {
                        for ($i = 0; $i < count($KeysParams); $i++) {
                                $arr_params = explode("|", $KeysParams[$i]);
                                //?是否None
                                $isNone = $ValueParams[$i] == "None" ? true : false;
                                //?判断验证是否必填
                                if ($arr_params[2] == "require") {
                                        if (empty($_GET[$arr_params[0]]) || $_GET[$arr_params[0]] == "")
                                                return Tool::msg(500, "$arr_params[1] { $arr_params[0] }不能为空！");
                                }
                                if ($arr_params[2] == "unrequire") {
                                        if (!isset($_GET[$arr_params[0]]))
                                                return Tool::msg(500, "$arr_params[1] { $arr_params[0] }不能为空！");
                                }
                                //*如果不是None那么走验证
                                if (!$isNone) {
                                        $arr_valueparams = explode(":", $ValueParams[$i]);
                                        //*验证规则
                                        self::VerRule($arr_params[1], $_GET[$arr_params[0]], $arr_valueparams[0], $arr_valueparams[1]);
                                }
                        }
                }
                //*POST请求
                if ($type == POST) {
                        for ($i = 0; $i < count($KeysParams); $i++) {
                                $arr_params = explode("|", $KeysParams[$i]);
                                //?是否None
                                $isNone = $ValueParams[$i] == "None" ? true : false;
                                //?判断验证是否必填
                                if ($arr_params[2] == "require") {
                                        //?判断是否为application/json
                                        if (Request::headers("Content-Type") == "application/json") {
                                                $params = Request::postparam();
                                                if (empty($params[$arr_params[0]]))
                                                        return Tool::msg(500, "$arr_params[1] { $arr_params[0] }不能为空！");
                                        } else {
                                                if (empty($params[$arr_params[0]]))
                                                        return Tool::msg(500, "$arr_params[1] { $arr_params[0] }不能为空！");
                                        }
                                }
                                if ($arr_params[2] == "unrequire") {
                                        if (Request::headers("Content-Type") == "application/json") {
                                                $params = Request::postparam();
                                                if (!isset($params[$arr_params[0]]))
                                                        return Tool::msg(500, "$arr_params[1]不能为空！");
                                        } else {
                                                if (!isset($params[$arr_params[0]]))
                                                        return Tool::msg(500, "$arr_params[1]不能为空！");
                                        }
                                }
                                //*如果不是None那么走验证
                                if (!$isNone) {
                                        $arr_valueparams = explode(":", $ValueParams[$i]);
                                        //*验证规则
                                        self::VerRule($arr_params[1], $params[$arr_params[0]], $arr_valueparams[0], $arr_valueparams[1]);
                                }
                        }
                }
        }
        /**
         * 验证规则
         * TODO用来限制前端传入参数。
         * @static
         * @param string $paramName 参数名字 必填
         * @param string|int $param 参数值 必填
         * @param string $type 规则类型 必填
         * @param string|int $rule 验证规则 必填
         */
        private static function VerRule(string $paramName, string|int $param, string $type, string|int $rule): mixed
        {
                return match ($type) {
                        "Max" => self::Max($paramName, $param, $rule),
                        "Min" => self::Min($paramName, $param, $rule),
                        "Between" => self::Between($paramName, $param, $rule),
                        "Beyond" => self::Beyond($paramName, $param, $rule),
                };
        }
        /**
         * 限制两数之外
         * @static
         * @param string $paramName 参数名字 必填
         * @param string|int $param 参数值 必填
         * @param string|int $rul 参数规则 必填
         */
        private static function Beyond(string $paramName, string|int $param, string|int $rule)
        {
                $arr_rule = explode(",", $rule);
                //?左边不能大于右边
                if ((int) $arr_rule[0] > (int) $arr_rule[1])
                        return Tool::abort("Between左边不能大于右边！");
                if ((int) $param > (int) $arr_rule[0] and (int) $param < (int) $arr_rule[1]) {
                        return Tool::abort($paramName . "不在两数之外！");
                }
        }
        /**
         * 限制两数之间
         * @static
         * @param string $paramName 参数名字 必填
         * @param string|int $param 参数值 必填
         * @param string|int $rul 参数规则 必填
         */
        private static function Between(string $paramName, string|int $param, string|int $rule)
        {
                $arr_rule = explode(",", $rule);
                //?左边不能大于右边
                if ((int) $arr_rule[0] > (int) $arr_rule[1])
                        return Tool::abort("Between左边不能大于右边！");
                if ((int) $param < (int) $arr_rule[0] or (int) $param > (int) $arr_rule[1]) {
                        return Tool::abort($paramName . "不在两数之间！");
                }
        }
        /**
         * 限制Max长度
         * @static
         * @param string $paramName 参数名字 必填
         * @param string|int $param 参数值 必填
         * @param string|int $rul 参数规则 必填
         */
        private static function Max(string $paramName, string|int $param, string|int $rule)
        {
                //?验证规则不能小于0
                if ($rule <= 0)
                        return Tool::abort("验证规则出现错误！");
                if (is_int((int) $rule) && $param > $rule) {
                        return Tool::abort($paramName . "长度过长！");
                }
        }
        /**
         * 限制Min长度
         * @static
         * @param string $paramName 参数名字 必填
         * @param string|int $param 参数值 必填
         * @param string|int $rul 参数规则 必填
         */
        private static function Min(string $paramName, string|int $param, string|int $rule)
        {
                //?验证规则不能小于0
                if ($rule <= 0)
                        return Tool::abort("验证规则出现错误！");
                if (is_int((int) $rule) && $param < $rule) {
                        return Tool::abort($paramName . "长度过短！");
                }
        }


}