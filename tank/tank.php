<?php
#+------------------------------------+
#  函数助手                           |
#  @By LL                         |
#+------------------------------------+
namespace tank;

use config\Code;
use config\SQL;
use tank\Tool\Tool as Tool;
use tank\Func\Func as Func;
use tank\View\View;
use tank\Request\Request;

/**
 * 获取当前路径执行函数
 */
if (!function_exists('getCurrentFunctionName')) {
    function getCurrentFunctionName()
    {
        $data = $_SERVER['REQUEST_URI'];
        $data = str_replace("\\", "", $data); //反斜杆
        $data = explode("/", $data);
        return $data[count($data) - 1];
    }
}
/**
 * 获取文档Document目录
 */
if (!function_exists('getDocumentUrl')) {
    function getDocumentUrl()
    {
        return getRoot() . "/document";
    }
}
/**
 * 获取常量语目录
 */
if (!function_exists("getConStantUrl")) {
    function getConStantUrl()
    {
        return getRoot() . "/constant";
    }
}
/**
 * 获取Config目录
 */
if (!function_exists('getConfigUrl')) {
    function getConfigUrl()
    {
        return getRoot() . "/config";
    }
}
/**
 * 获取Root目录路径
 */
if (!function_exists('getRoot')) {
    function getRoot()
    {
        return str_replace("tank", '', __DIR__);
    }
}
/**
 * 获取Public公共目录
 */
if (!function_exists('getPublicUrl')) {
    function getPublicUrl()
    {
        return getRoot() . "/public";
    }
}
/**
 * 获取视图层路径
 */
if (!function_exists('getViewUrl')) {
    function getViewUrl()
    {
        return getRoot() . "/views";
    }
}
/**
 * 欢迎语
 * @return string
 */
if (!function_exists("Weclome")) {
    function Weclome()
    {
        header("Content-Type:text/html");
        include(getRoot() . '/public/then/weclome.html');
    }
}
/**
 * 返回成功响应
 */
if (!function_exists("Success")) {
    /**
     * @param string $message 返回提示内容 必填
     * @param array $data 返回数据内容 选填
     */
    function Success(string $message, array|object $data = [], string $function = '')
    {
        echo Tool::Message(200, $message, $data, func: $function);
    }
}
/**
 * 返回失败响应
 */
if (!function_exists("Error")) {
    /**
     * @param string $message 返回提示内容 必填
     * @param array $data 返回数据内容 选填
     */
    function Error(string $message, array $data = [], string $function = '')
    {
        echo Tool::Message(404, $message, $data, func: $function);
        die;
    }
}
/**
 * 强制报错
 * TODO强制错误！
 */
if (!function_exists("Abort")) {
    /**
     * @param string $message 返回提示内容 必填
     */
    function Abort(string $message)
    {
        return Tool::abort($message);
    }
}
/**
 * 获取当前时间
 * TODO获取当前时间
 */
if (!function_exists('GetNewDate')) {
    function GetNewDate()
    {
        return Tool::GetNewDate("New");
    }
}
/**
 * 生成Guid
 * TODO生成一条Guid
 */
if (!function_exists('Guid')) {
    /**
     * @param string $data 生成内容 选填
     */
    function Guid(string $data = '')
    {
        return Tool::AutomaticID($data);
    }
}
/**
 * 自动路由调用函数
 * TODO自动实现路由验证回调对应函数
 */
if (!function_exists('AutoVerCallFunction')) {
    function AutoVerCallFunction(mixed $Class)
    {
        Func::AutoVerCallFunction($Class);
    }
}
/**
 * 参数验证器
 * TODO前端传入参数验证
 */
if (!function_exists('BathVerParams')) {
    function BathVerParams(string $type, array $param)
    {
        $type = match ($type) {
            "GET" => 1,
            "POST" => 2,
        };
        Func::BathVerParams($type, $param);
    }
}
/**
 * 友好交互变量输出
 * TODO自制的dump输出变量
 */
if (!function_exists("dump")) {
    /**
     * @param mixed $var 变量值 必填
     */
    function dump(mixed $var)
    {
        header("Content-Type:text/html");
        //!针对不同类型
        if (is_string($var)) {
            echo "类型:" . gettype($var) . '-长度:' . strlen($var) . '-值:' . $var;
        }
        if (is_int($var)) {
            echo "类型:" . gettype($var) . '-长度:' . strlen($var) . '-值:' . $var;
        }
        if (is_array($var)) {
            $con = "";
            //*获取键
            $var_Key = array_keys($var);
            //*获取值
            $var_Value = array_values($var);
            for ($i = 0; $i < count($var_Key); $i++) {
                $con .= "键:" . $var_Key[$i] . "-类型:" . gettype($var_Value[$i]) . '-长度:' . strlen($var_Value[$i]) . '-值:' . $var_Value[$i] . '|';
            }
            $con = substr($con, 0, strlen($con) - 1);
            echo $con;
        }
    }
}
/**
 * 快速绑定
 * TODO绑定字段
 */
if (!function_exists("bind")) {
    /**
     * @param string $infomat 前缀字段名字-必填
     * @param string|array $behind 后缀字段名字-必填
     */
    function bind(string $infomat, string|array $behind)
    {
        if (is_array($behind)) {
            $con = [];
            for ($i = 0; $i < count($behind); $i++) {
                array_push($con, Tool::Bind($infomat, $behind[$i]));
            }
            return $con;
        }
        return Tool::Bind($infomat, $behind);
    }
}
/**
 * JSON格式输出
 * TODO以JSON格式返回数据
 */
if (!function_exists("json")) {
    /**
     * @param array 需要转化为JSON的值 必填
     */
    function json(array $data)
    {
        echo Tool::JSON($data);
    }
}
/**
 * 获取当前路径
 * TODO获取当前浏览器访问路径的最后一个行段
 */
if (!function_exists('getCurrentUrl')) {
    function getCurrentUrl()
    {
        $Url = explode('/', Func::getUrl());
        $Url = explode('?', $Url[count($Url) - 1]);
        return $Url[0];
    }
}
/**
 * 页面跳转
 * TODO类似于HTML跳转
 */
if (!function_exists('LocationTo')) {
    function LocationTo(string $to)
    {
        header("Location:$to");
    }
}
/**
 * Verification验证规则引入
 * TODO用来快速引入并且对验证规则进行获取。
 */
if (!function_exists("VerificationInclude")) {
    /**
     * @param string $PHPName PHP文件名字 必填
     */
    function VerificationInclude(string $PHPName)
    {
        return require(getRoot() . "app/verification/$PHPName.php");
    }
}
/**
 * 获取配置JSON文件内容
 * TODO用来获取app.json内容
 */
if (!function_exists('getAPPJSON')) {
    function getAPPJSON()
    {
        $JSON = Tool::FileRead(getRoot() . 'app.json');
        return json_decode($JSON);
    }
}
/**
 * STOP强制停止网络请求
 * TODO用来强行停止请求接口
 */
if (!function_exists('stop')) {
    function stop()
    {
        header("HTTP/1.1 500 Internal Server Error");
    }
}
/**
 * 数据库连接
 * TODO连接对应数据库，请根据Database.php来修改
 */
if (!function_exists("SQLConnect")) {
    function SQLConnect()
    {
        /**
         * 多数据库支持
         */
        if (SQL::$ConnectSQL['SQLType'] != "All") {
            match (SQL::$ConnectSQL['SQLType']) {
                'MongoDB' => \tank\MG\MG::Connect(),
                'MySQL' => \tank\SQL\MySQL::Connect(),
            };
        } else {
            \tank\MG\MG::Connect();
            \tank\SQL\MySQL::Connect();
        }
    }
}
/**
 * 全局错误配置
 * TODO抑制所有错误，提示Tank的错误提示内容。
 */
if (!function_exists("OverallSituationErrorConfig")) {
    function OverallSituationErrorConfig(bool $isReturnJson = false)
    {
        error_reporting(0);
        set_error_handler(function ($data, $content, $url, $line) use ($isReturnJson) {
            if ($isReturnJson) {
                echo Tool::Message(404, $content);
            } else {
                if ($data != 0) {
                    if (empty($content)) {
                        $content = "暂时没有错误提示内容!";
                    }
                    \tank\Error\error::create($content, $url, $line);
                } else
                    \tank\Error\error::create("当前错误出现问题!", __FILE__, __LINE__);
            }
        });
    }
}
/**
 * 获取签名
 * TODO当调用APP类时会自动生成请求头签名。
 */
if (!function_exists("getAutograph")) {
    function getAutograph()
    {
        // return apache_response_headers()["Autograph"];
        return Request::response('Autograph');
    }
}
/**
 * 视图层渲染
 * TODO快速渲染HTML页面
 */
if (!function_exists("view")) {
    function view(string $url, array $params = [], array $attr = [])
    {
        View::Start($url, $params, $attr);
    }
}
