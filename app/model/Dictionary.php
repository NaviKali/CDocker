<?php
/**
 * 字典
 */
namespace app\model;

class Dictionary extends \tank\MD\MD
{
        /**Key绑定 */
        public static $Key = "dictionary_id";
        /**Guid绑定 */
        public static $Guid = ["dictionary_guid", "dictionary_name"];
        /**显示字段 */
        public static $field = [
                'dictionary_id' => self::SHOW,
                'dictionary_guid' => self::SHOW,
                'dictionary_name' => self::SHOW,
                'dictionary_type' => self::SHOW,
                'dictionary_key' => self::SHOW,
                'dictionary_value' => self::SHOW,
                'dictionary_father_guid'=>self::SHOW,
        ];
        /**写入字段 */
        public static $writefield = [
                "dictionary_name"=>"字典名字",
                "dictionary_type" => "字典类型",
                "dictionary_key" => "字典Key",
                "dictionary_value" => "字典value",
                "dictionary_father_guid"=>"字典父级Guid"
        ];
        /**开启软删除 */
        public static $OpenSoftDelete = true;
        /**软删除字段 */
        public static $SoftDeleteField = null;
        /**开启其余字段写入 */
        public static $OpenOtherWriteField = true;
        /**其余字段写入 */
        public static $OtherWriteField = [
                'create' => "dictionary_create_time",
                'update' => "dictionary_update_time",
        ];
        /**开启业务姓名字段写入 */
        public static $UserNameWriteField = true;
        /* 默认业务姓名字段 */
        public const DEFAULTUSERNAME = 'User';
        /**默认业务姓名字段值 */
        public const DEFAULTUSERNAMEVALUE = "admin";
        /**
         * 添加前
         */
        public static function onBeforeCreate()
        {
        }
        /**
         * 添加后
         */
        public static function onAfterCreate()
        {
        }
        /**
         * 修改前
         */
        public static function onBeforeUpdate()
        {
        }
        /**
         * 修改后
         */
        public static function onAfterUpdate()
        {

        }
        /**
         * 删除前
         */
        public static function onBeforeDelete()
        {
        }
        /**
         * 删除后
         */
        public static function onAfterDelete()
        {
        }

}