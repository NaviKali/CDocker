<?php
/**
 * 读取Tank的环境变量
 */
namespace tank\Env;

use tank\Error\httpError;
use function tank\getRoot;
use tank\Attribute\Attribute;
/**
 * 常量
 */
define("DEFAULT_GET", 100);

class env
{
    /**
     * 环境变量的集合
     */
    public static array $EnvList = [];
    /**
     * 构造拿取
     * @access public
     * @param string $envfile Env文件 选填 默认为 tank
     */
    public function __construct(string $envfile = "tank")
    {
        (new Attribute("FUNCTION", "环境变量是不可缺少的东西。"));
        if (file_exists(getRoot() . "env/{$envfile}.env")) {
            $env = file(getRoot() . "env/{$envfile}.env");
            self::$EnvList = $env;
        } else {
            \tank\Error\error::create("没有该环境变量文件!", __FILE__, __LINE__);
        }
    }
    /**
     * 获取环境变量
     * @static
     * @access public
     * @param string $key 环境变量键 选填 默认为 DEFAULT_GET
     * @return array|string
     */
    public function get(string $key = DEFAULT_GET):array|string
    {
        (new Attribute("FUNCTION", "可以快速拿到对应环境变量。"));
        $envList = self::$EnvList;
        $env = [];

        foreach ($envList as $k => $v) {
            $env[trim(explode("=", $v)[0])] = trim(explode("=", $v)[1]);
        }

        if ($key != DEFAULT_GET) {
            //?是否存在你索要的环境变量的key 有则给 否则为[]
            if (in_array($key, array_keys($env))) {
                return $env[$key];
            } else {
                return [];
            }
        }
        return $env;
    }
    /**
     * 是否存在某一个环境变量
     * @access public
     * @param string $value 环境变量值 必填
     * @param string $arrayType 数组类型 选填 默认为 "key"  ["key" or "value"]
     * @return bool|httpError
     */
    public function has(string $value, string $arrayType = "key"): bool|httpError
    {
        (new Attribute("FUNCTION", "判断是否存在某一个环境变量是提前主要条件!"));
        if ($arrayType == "key" or $arrayType == "value") {

            if ($arrayType == "key")
                return in_array($value, array_keys($this->get())) ? true : false;

            if ($arrayType == "value")
                return in_array($value, array_values($this->get())) ? true : false;

        }
        //!错误排除
        else {
            return throw new httpError("参数类型错误!#" . __FUNCTION__);
        }

        return true;
    }
}