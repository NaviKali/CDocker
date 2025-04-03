<?php

namespace tank\Cookie;

use tank\Tool\Tool;

class Cookie
{
        /* Cookie时长 */
        public static $CookieLongTime;
        /**
         * Cookie构造函数
         * @param int $time 设置Cookie时长
         */
        public function __construct(int $time)
        {
                $this->CookieLongTime = $time;
        }
        /**
         * 设置一个Cookie
         * @static
         * @param string $name Cookie名 必填
         * @param mixed $value Cookie值 必填
         * @param int $time Cookie过期时间->秒 必填 也可以是构造函数中的时间值
         * @return mixed
         */
        public static function SetCookie(string $name, mixed $value, int $time)
        {
                $time = $time ?? self::$CookieLongTime;
                setrawcookie($name, $value, time() + $time);
        }
        /**
         * 批量设置Cookie
         * @access public
         * @static
         * @param array $name Cookie名 必填
         * @param array $value Cookie值 必填
         * @param array $time Cookie到期时间->秒 必填
         */
        public static function BatchSetCookie(array $name, array $value, array $time)
        {
                //?参数验证
                $nameCount = count($name);
                $valueCount = count($value);
                $timeCount = count($time);
                if ($nameCount != $valueCount && $nameCount != $timeCount)
                        return Tool::abort("Cookie设置长度错误！");
                for ($i = 0; $i < $nameCount; $i++) {
                        if (!is_string($name[$i]) or !is_string($value[$i]) or !is_int($value[$i]))
                                return Tool::abort("Cookie设置字段错误！");
                        self::SetCookie($name[$i], $value[$i], $time[$i]);
                }

        }
        /**
         * 读取一个Cookie
         * @static
         * @access public
         * @param string $name Cookie名
         * @return array
         */
        public static function ReadCookie(string $name): array
        {
                return isset($_COOKIE[$name]) ? $_COOKIE[$name] : Tool::abort("没有该Cookie！");
        }
        /**
         * 删除一个Cookie
         * @static
         * @param string $name Cookie名
         * @return void
         */
        public static function DeleteCookie(string $name):void
        {
                setrawcookie($name, '', time() - 10000);
        }
        /**
         * 批量删除Cookie
         * @static
         * @param array $name Cookie名
         */
        public static function BatchDeleteCookie(array $name)
        {
                for ($i = 0; $i < count($name); $i++) {
                        self::DeleteCookie($name[$i]);
                }
        }

}
