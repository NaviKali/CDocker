<?php

namespace tank\Seesion;

class Session
{
        /**
         * 开启Session会话
         */
        public function __construct()
        {
                session_start();
        }
        /**
         * 创建Session会话
         * @access public
         * @param string $name Session名
         * @param mixed $value Session值
         * @return void
         */
        public function CreateSession(string $name, mixed $value): void
        {
                $_SESSION[$name] = $value;
        }
        /**
         * 删除Session会话
         * @access public
         * @param string $name Session名
         */
        public function DeleteSession(string $name): void
        {
                if (isset($_SESSION[$name])) {
                        unset($_SESSION[$name]);
                }
                 \tank\Error\error::create("没有该Session会话", __FILE__, __LINE__);
        }

}