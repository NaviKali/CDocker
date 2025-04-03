<?php
namespace config;


/**
 * 自动引入Exec命令
 */
AutomaticInclude(str_replace('config','',getcwd())."/.exec/FromExec");


/**
 * 自动引入文件PHP
 * TODO用来自动识别加载文件并且自动include引入到Base基层中。
 * @param string  $Url
 */
function AutomaticInclude(string $Url)
{
        $file_list = glob($Url . "/*.php");
        foreach ($file_list as $file) {
                $file = str_replace("\\", "/", $file);
                BatchInclude($file);
        }
}


/**
 * !批量includePHP文件
 * TODO 用来批量引入PHP文件，方便省时间。
 * @param string|array $url 访问PHP文件地址
 * @return mixed
 */
function BatchInclude(string|array $url)
{
        if (is_string($url)) {
                include($url);
        }
        if (is_array($url)) {
                for ($i = 0; $i < count($url); $i++) {
                        include($url[$i]);
                }
        }
}