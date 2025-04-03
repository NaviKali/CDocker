<?php

namespace config;

/**
 * (配置)MongoDB数据库
 */
use tank\Env\env;


class SQL
{
        /**
         * 数据库类型选择
         * @var array
         */
        public static array $ConnectSQL =
                [
                        /* 数据库类型 */
                        /**
                         * All[支持全部数据库连接]
                         */
                        "SQLType" => "MongoDB",

                ];
        /**
         * MongoDB数据库配置
         * @var array
         */
        public static array $MongoDB =
                [
                        /* 数据库地址 */
                        //!如果启用容器的话，连接本地名称需要改为对应docker-compose.yml mongodb的名称 [默认:mongo]
                        "SQLLocalhost" => "localhost",
                        /* 数据库端口 */
                        //* MongoDB容器端口默认为：外部服务->27018，内部服务->27017
                        "SQLPort" => 27017,
                        /* 数据库名称 */
                        "SQLDatabaseName" => "",
                ];
        /**
         * MySQL数据库配置
         * @var array
         */
        public static array $MySQL =
                [
                        /* 数据库地址 */
                        "SQLLocalhost" => "localhost",
                        /* 数据库端口 */
                        "SQLPort" => 3306,
                        /* 数据库名称 */
                        "SQLDatabaseName" => "",
                        /* MySQL用户 */
                        "MySQL_User" => "",
                        /* MySQL密码 */
                        "MySQL_PassWord" => "",
                ];

}

