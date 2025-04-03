<?php
#+---------------------------***----------------------------------+
#|                         T A N K                                |
#|                        BY:LL                                   |
#|                       App服务协议                               |
#+---------------------------***----------------------------------+
#|                       入 口 文 件                               |
#+---------------------------***----------------------------------+
namespace App;

/**
 * !Composer第三方加载
 */
require_once "../../vendor/autoload.php";
require_once "../../config/Base.php";
/**
 * !WebHttp请求
 */
use tank\Web\http as WebHttp;
use function tank\dump;


try {
        (new WebHttp(__FILE__))->Mount([])->App()->Client();
} catch (\Exception $e) {
        return dump($e);
}