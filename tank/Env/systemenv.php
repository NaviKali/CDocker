<?php

namespace tank\Env;

/**
 * 系统环境变量
 */
class systemenv
{
    /**
     * 全部环境变量列表
     * @var array
     */
    protected static array $EnvList;
    /**
     * 存入环境变量
     * @access public
     * @param string $key 环境变量key 必填
     * @param mixed $value 环境变量value 必填
     * @return void
     */
    public static function addEnv(string $key, mixed $value): void
    {
        putenv("$key=$value");
    }
    /**
     * 获取系统环境变量
     * @access public
     * @param string|array $env 获取需要的环境变量名 选填 默认为 null
     * @return string|array
     */
    public static function getSystemEnv(string|array $env = null): string|array
    {
        if ($env == null)
            return getenv();
        if (is_string($env))
            return getenv()[$env];
        if (is_array($env)) {
            $arr = [];
            foreach ($env as $k => $v) {
                array_push($arr, getenv()[$v]);
            }
            return $arr;
        }
        
        return null;
    }
}
