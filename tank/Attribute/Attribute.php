<?php

namespace tank\Attribute;

/**
 * 特性(Attribute) / 注解定义
 */
#[Attribute]
class Attribute
{
        /* 特性集群 */
        public static $AttributeArr = [];
        /**
         * 创建一个特性
         * TODO特性用来存储需求值或注解的好功能(灵感来源:C#)。
         * @author LL
         * @static
         * @access public
         * @param string $type 类型 必填
         * @param string $data 提示内容/注解 选填 默认为 ""
         * @param mixed $other 其余条件 选填 默认为 []
         * @return mixed
         */
        public function __construct(string $type, string $data = "", mixed $other = [])
        {
                //*验证类型
                self::VerAttributeType($type);
                //*存值
                array_push(self::$AttributeArr, $other == [] ? ["type" => $type, "data" => $data] : ["type" => $type, "data" => $data, "other" => $other]);
        }
        /**
         * 拿取特性值 //*即反射
         * TODO定义特性然后拿取特性的值。
         * @static
         * @access public
         * @param mixed $index 索引值 选填 默认为 特性集群的最后一个/即最后一个特性
         */
        public static function getAttribute(mixed $index = 'index')
        {
                //*展示存储
                $con = [];
                /**
                 * 字符串处理 -> 取决于//!data值
                 */
                if (is_string($index)) {
                        if ($index == 'index') {
                                return self::$AttributeArr[sizeof(self::$AttributeArr) - 1];
                        }
                        for ($i = 0; $i < count(self::$AttributeArr); $i++) {
                                if (preg_match("/$index/", self::$AttributeArr[$i]['data'])) {
                                        array_push($con, self::$AttributeArr[$i]);
                                }

                        }
                        return $con[sizeof($con) - 1];
                }
                /**
                 * 整数类型处理
                 */
                if (is_int($index)) {
                        //?是否存在于特性集群中。
                        return array_key_exists($index, self::$AttributeArr) ? self::$AttributeArr[$index] : \tank\Error\error::create("特性未出现过，请再次确认！");
                }
        }
        /**
         * 类型验证
         * @access public
         * @static
         * @param string $type 验证类型 必填 
         * @return mixed
         */
        public static function VerAttributeType(string $type): mixed
        {
                return match ($type) {
                        'DEBUG' => \tank\Error\error::create("DEBUG类型错误，请及时修改！", __FILE__, __LINE__),
                        'FUNCTION' => null,
                        'VOID' => null,
                        default => \tank\Error\error::create("未知特征类型，请及时修改！", __FILE__, __LINE__),
                };
        }
}