<?php
/**
 * Token
 */
namespace app\model;

class Token extends \tank\MD\MD
{
        /**Key绑定 */
        public static $Key = "token_id";
        /**Guid绑定 */
        public static $Guid = ["token_guid", "token_value"];
        /**显示字段 */
        public static $field = [
                'token_guid' => self::SHOW,
                'token_value' => self::SHOW,
                'token_create_time' => self::SHOW,
                'token_update_time' => self::SHOW,
        ];
        /**写入字段 */
        public static $writefield = [
                'token_value' => "token值"
        ];
        /**开启软删除 */
        public static $OpenSoftDelete = true;
        /**软删除字段 */
        public static $SoftDeleteField = null;
        /**开启其余字段写入 */
        public static $OpenOtherWriteField = true;
        /**其余字段写入 */
        public static $OtherWriteField = [
                'create' => "token_create_time",
                'update' => "token_update_time",
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
                (new \app\model\Token)->select() ? die : null;
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
                (new \app\model\Token)->select() ? null : die;
        }
        /**
         * 删除后
         */
        public static function onAfterDelete()
        {

        }

}