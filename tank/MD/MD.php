<?php
namespace tank\MD;

/**
 * BaseModel 基层Model模型层 
 * @author LL
 */


use tank\Error\httpError;
use tank\MG\MG as MG;
use tank\Tool\Tool;

class MD extends MG
{
        /* 显示 */
        public const SHOW = 1;
        /* 隐藏 */
        public const HIDDEN = 0;
        /* 默认业务姓名字段 */
        public const DEFAULTUSERNAME = 'User';
        /**默认业务姓名字段值 */
        public const DEFAULTUSERNAMEVALUE = "admin";
        /* 默认写入软删除字段(请根据需求给予) */
        public static $normalSoftDeleteField = "deletetime";
        /* 默认新建字段 */
        public static $normalCreateField = "createtime";
        /* 默认修改字段 */
        public static $normalUpdateField = "updatetime";
        /* 当前类名 */
        public static $className;
        /* 定义索引 */
        public static $defineindex = [];
        /* 写入字段 */
        public static $writefield;
        /* 定义filed字段显示 */
        public static $field;
        /* 软删除 */
        public static $OpenSoftDelete = false;
        /* 软删除时间字段 */
        public static $SoftDeleteField = null;
        /* 其余业务字段写入 */
        public static $OpenOtherWriteField = false;
        /* 其余业务时间字段 */
        public static $OtherWriteField = null;
        /* Key绑定(定义排序id值) */
        public static $Key = null;
        /* Key绑定状态 */
        protected static $KeyStatus = false;
        /* Guid绑定(Guid乱码) */
        //!注意Guid的规范是[Guid字段名字，Guid要双向绑定的字段或值]
        public static $Guid = null;
        /*Guid绑定状态 */
        protected static $GuidStatus = false;
        /* 业务姓名写入 */
        public static $UserNameWriteField = false;
        /* 业务姓名字段 */
        public static $UserNameField = self::DEFAULTUSERNAME;
        /**业务姓名字段值 */
        public static $UserNameFieldValue = self::DEFAULTUSERNAMEVALUE;
        /**
         * 模型层->构造函数
         * TODO模型中间处理构造
         */
        public function __construct()
        {
                $this->getClass(); //*直接获取类名
                $this->isDefineKey(); //?判断是否绑定Key键
                $this->isDefineGuid(); //?判断是否绑定Guid
                $this->isOpenSoftDelete(); //?判断是否开启软删除
                $this->isOpenOtherWriteField(); //?判断是否开启写入其余字段
                $this->isOpenUserNameWriteField();//?判断是否开启业务姓名写入
                $this->IsDefineIndex();//?判断是否定义索引
                (new MG($this::$className));//*实例化当前数据表
        }
        /**
         * 判断是否定义索引
         * TODO定义索引可以快速查询，减少时间成本
         */
        protected function IsDefineIndex()
        {
                if ($this::$defineindex == [])
                        return;
                if ($this::$defineindex != [] and count(self::$defineindex) != 3)
                        return Tool::abort("定义索引[索引名称，索引字段，排序{1:升序,-1:降序}]");
                $this->CreateIndex($this::$defineindex);
        }
        /**
         * 判断是否开启业务姓名写入
         * TODO用来判断是否开启业务姓名
         */
        protected function isOpenUserNameWriteField()
        {
                $this::$UserNameWriteField ? ['field' => self::$UserNameField, 'value' => self::$UserNameFieldValue] : null;
        }
        /**
         * 判断是否定义Guid
         * TODO用来判断是否绑定Guid
         */
        protected function isDefineGuid()
        {
                //?判断定义Guid双向绑定是否在写入字段里面
                if (!in_array($this::$Guid[1], array_keys($this::$writefield)))
                        return Tool::abort("Guid双向绑定值不存在与写入字段！");
                //?排除异常
                if (isset($this::$Guid)) {
                        if (!is_array($this::$Guid))
                                return Tool::abort("Guid绑定错误！");
                        if (count($this::$Guid) != 2)
                                return Tool::abort("Guid绑定错误！");
                }
                $this::$GuidStatus = isset($this::$Guid) ? true : false;
        }
        /**
         * 判断是否定义Key
         * TODO用来判断是否绑定Key键
         */
        protected function isDefineKey()
        {
                $this::$KeyStatus = isset($this::$Key) ? true : false;
        }
        /**
         * 其余字段写入 -> 判定
         * TODO用来判断是否开启写入其余字段的时间戳。
         * @throws httpError
         */
        protected function isOpenOtherWriteField()
        {
                $is = !$this::$OpenOtherWriteField ? false : true;
                if (!$is)
                        $this::$OtherWriteField == null;
                if ($this::$OtherWriteField == [] and $is)
                        throw new httpError("请定义其余字段写入!");
        }
        /**
         * 软删除 -> 判定
         * TODO用来判断是否开启模型层中的软删除功能。
         * @throws httpError
         */
        protected function isOpenSoftDelete()
        {
                $is = !$this::$OpenSoftDelete ? false : true;
                if (!$is)
                        $this::$SoftDeleteField == null;
                if ($this::$SoftDeleteField == null and $is)
                        throw new httpError("请定义软删除字段!");
        }
        /**
         * 获取子类名
         * TODO用来直接获取类。
         */
        private function getClass()
        {
                $className = get_class($this);
                $className = strtolower($className);
                $className = str_replace("app\\model\\", "", $className);
                $this::$className = $className;
                return $this;
        }

}
