<?php
namespace app\middleware;

use tank\Error\httpError;


class localaccessmiddleware
{
     /**
      * 本地访问 | 禁止一切外部访问
      * @access public
      * @static
      */
     public static function Handle()
     {
          /**
           * 填写ip或域名
           */
          self::injunction('');
     }

     /**
      * 禁止令
      */
     public static function injunction(string $local): void
     {
          // 检查脚本是否通过命令行运行
          if (PHP_SAPI === 'cli') {
               throw new httpError('脚本不能通过命令行访问。');
          }
          // 检查脚本是否被正确访问
          if ($_SERVER['HTTP_HOST'] != $local) {
               throw new httpError('外部访问被禁止。');
          }
     }
}