<?php
namespace tank\Socket;

/**
 * Socket(请确认是否在php.ini开启Socket)
 */

class Socket
{
        /**
         * 创造Socket体
         */
        public static $Socket;
        public function CreateSocket()
        {
                $this::$Socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
                return $this;
        }
        /**
         * Bind绑定
         * @param int $port 选填 默认为8888
         */
        public function Bind(int $port = 8888)
        {
                socket_bind($this::$Socket, 0, $port);
                return $this;
        }
        /**
         * 监听Socket
         */
        public function listen()
        {
                socket_listen($this::$Socket);
                return $this;
        }
        /**
         * Socket提权
         */
        public function accept()
        {
           return  socket_accept($this::$Socket);
        }
        /**
         * Socket写入
         * @access public
         * @author LL
         * @param mixed $Socket Socket体 必填
         * @param string $Message 写入内容 必填
         */
        public static function SocketWrite(mixed $Socket,string $Message)
        {
                socket_write($Socket,$Message);
        }
        /**
         * Socket读取
         * @access public
         * @author LL
         * @param mixed $Socket Socket体 必填
         * @param int $size 字节 选填 默认为1024
         * @return mixed 读取到的值
         */
        public static function SocketRead(mixed $Socket,int $size = 1024):mixed
        {
              return socket_read($Socket,$size);
        }
}