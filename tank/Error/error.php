<?php

namespace tank\Error;

use function tank\getRoot;

class error
{
        /**
         * 创造一个访问错误页面
         * @author LL
         * @static
         * @access public
         * @param string $data 错误内容展示 必填
         * @param string $file 当前文件路径 选填
         * @param string $line 当前文件行数 选填
         */
        public static function create(string $data, string $file = '', string $line = '')
        {
                header("HTTP/1.1 500 Internal Server Error");
                header("Content-Type:text/html");
                $workdir = getcwd();
                $locationname = gethostname();
                $ip = gethostbyname(gethostname());
                include (getRoot() . "/public/then/error.html");
                die();
        }

}