<?php

namespace tank\MG;

use tank\Error\httpError;

/**
 * BaseMongoDB 基层操作MongoDB数据库
 * @author LL
 */

include('../../config/Database.php');
use config\SQL as SQL;
use MongoDB\Driver\Manager as Manager;
use MongoDB\Driver\Exception as DriverException;
use MongoDB\Client as Client;
use MongoDB\Driver\Query as Query;
use tank\Tool\Tool as Tool;
use MongoDB\Driver\Command as MongoDBCommand;
use MongoDB\Driver\BulkWrite as BulkWrite;
use tank\Log\Log\Log as Log;
use tank\Request\Request;

use function tank\getRoot;

/**
 * MG -> 接口
 */
interface IMG
{
        /**
         * 分页
         */
        public function page(int $page, int $limit);
        /**
         * 强制删除
         */
        public function forcedelete();
        /**
         * 查询过滤
         */
        public function selectFilter(?array $filter = []);
        /**
         * 查询后先执行
         */
        public function selectAfterVoid(\Closure $void);
        /**
         * 查询前先执行
         */
        public function selectBeforeVoid(\Closure $void, bool $defaultSelect = true);
        /**
         * 保存
         */
        public function save(array $data);
        /**
         * 修改单条字段
         */
        public function updateOne(string $field, mixed $value);
        /**
         * Guid删除
         */
        public function GuidDelete(string $Guid);
        /**
         * Key键删除
         */
        public function KeyDelete(int $keyNumber);
        /**
         * 开启写入业务姓名字段
         */
        public function OpenWriteUserNameField(array $data);
        /**
         * 数值（增减）
         */
        function inc(array $data, string $field, int $number);
        /**
         * 连表
         */
        function Join(string $assOutside, string $alikeFont);
        /**
         * 查询数据
         */
        function find(?array $filter = []);
        /**
         * 排序
         */
        function Order(string $fieldName, string $order = "asc");
        /**
         * 显示数量
         */
        function limit(int $number);
        /**
         * 跳过数量
         */
        function skip(int $number);
        /**
         * 删除
         */
        function delete(bool $isForce = false);
        /**
         * 修改
         */
        function update(array $update);
        /**
         * 查询
         */
        function select(bool $isGetCount = false);
        /**
         * 筛选字段
         */
        function field(array $field);
        /**
         * 获取单条数据
         */
        function Once();
        /**
         * 筛选条件
         */
        function where(array $filter);
        /**
         * 注释内容
         */
        function comment(string $data);
        /**
         * 模型层添加
         */
        function Modelcreate(array $data);
        /**
         * 多添加
         */
        function createMany(array $data);
        /**
         * 批量添加
         */
        function createBatch(array $data, int $number);
        /**
         * 添加
         */
        function create(array $data);
        /**
         * 使用表/切换表
         */
        function use(string $collectionName);
}


/**
 * MG -> Mongo 数据库的操作类
 */
class MG implements IMG
{
        /**Options选项 */
        private array $options = [];
        /* 最大添加次数 */
        private const MaxCreateBath = 10;
        /**
         * @param string $collectionName 集合名
         */
        public function __construct(?string $collectionName = "")
        {
                //!必须要小写模式
                $this::$CollectionName = strtolower($collectionName);
        }
        /**
         * 数据库实例化
         */
        public static $manager;
        /**
         * 选中数据库
         */
        public static $mongoDB;
        /**
         * 选中集合
         */
        public static $CollectionName;
        /**
         * 数据库连接状态
         * @static
         */
        public static $ClientStatus = false;
        /**
         * 注释内容
         */
        public static $comment;
        /**
         * 选中文档数据
         */
        public mixed $documentContent;
        /**
         * 筛选条件
         */
        public static $filter = []; //*默认查询全部内容
        /**
         * 筛选字段
         */
        public static $field = [];
        /**
         * 定义索引
         * @var array
         */
        public static $defineindex = [];
        /**
         * 限制长度
         */
        public int $limit;
        /**
         * 跳过长度
         */
        public int $skip;
        /**
         * 修改条件
         */
        public static $update;
        /**
         * 排序字段
         */
        public static $order = [];
        /**
         * (模型层)写入字段
         */
        public static $writefield = null;
        /* 软删除 */
        public static $OpenSoftDelete = false;
        /* 软删除时间字段 */
        public static $SoftDeleteField = null;
        /* 其余业务字段写入 */
        public static $OpenOtherWriteField = false;
        /* 其余业务时间字段 */
        public static $OtherWriteField = null;
        /**
         * 查询前
         */
        private static function onBeforeSelect()
        {
        }
        /**
         * 查询后
         */
        private static function onAfterSelect()
        {
        }
        /**
         * 添加前
         */
        private static function onBeforeCreate()
        {
        }
        /**
         * 添加后
         */
        private static function onAfterCreate()
        {
        }
        /**
         * 修改前
         */
        private static function onBeforeUpdate()
        {
        }
        /**
         * 修改后
         */
        private static function onAfterUpdate()
        {

        }
        /**
         * 删除前
         */
        private static function onBeforeDelete()
        {

        }
        /**
         * 删除后
         */
        private static function onAfterDelete()
        {

        }
        /**
         * 是否连表
         */
        private static $isJoin = false;
        /**
         * 连表后的值
         */
        private static $JoinValue = [];
        /* Key绑定(定义排序id值) */
        public static $Key = null;
        /* Key绑定状态 */
        private static $KeyStatus = false;
        /* Guid绑定(Guid乱码) */
        public static $Guid = null;
        /*Guid绑定状态 */
        protected static $GuidStatus = false;
        /* 业务姓名写入 */
        public static $UserNameWriteField = false;
        /* 业务姓名字段 */
        public static $UserNameField = null;
        /**业务姓名字段值 */
        public static $UserNameFieldValue = null;
        /**
         * Tank支持操作
         */
        public array $TankOfDo = [
                'useTankDictionary' => [],
        ];
        /**
         * 使用Tank字典
         * @access public
         * @param string|array $index string->获取key array->获取value
         * @param string $field 字段 选填 如果要获取key就填写。
         * @return self
         */
        public function useTankDictionary(string|array $index, array $field = []): self
        {
                if (is_string($index))
                        $this->TankOfDo["useTankDictionary"] = ["type" => "key", "main" => ["dic" => $index, "field" => $field]];
                if (is_array($index))
                        $this->TankOfDo["useTankDictionary"] = ["type" => "value", ["main"] => ["field" => $index]];
                return $this;
        }
        /**
         * 创建索引
         * @access public
         * @param array $index 索引 必填
         * @return void
         */
        protected function CreateIndex(array $index): void
        {
                $manager = self::$manager;
                $index = [
                        'name' => $index[0], //*索引名称
                        'key' => [$index[1] => $index[2]]  //* 索引字段，1表示升序，-1表示降序
                ];
                $command = new MongoDBCommand([
                        'createIndexes' => 'users', // 集合名称
                        'indexes' => [$index]
                ]);
                $manager->executeCommand('testdb', $command);
        }
        /**
         * 查询过滤
         * @access public
         * @date 2024/04/10
         * @param array $filter 过滤条件 选填 默认为 [] 格式为 [字段,条件,数值] "二维数组"
         * @throws httpError 请求错误 异常
         * @return array
         */
        public function selectFilter(?array $filter = []): array
        {
                //*查询
                $this->select();

                if ($this->documentContent == [])
                        return [];

                $ArrayDimension = Operate::RecognitionArrayDimension($filter);
                //!错误排除
                if ($ArrayDimension != 2)
                        throw new httpError("参数类型错误!");

                return $this->MGFilter($filter, $this->documentContent);

        }

        /**
         * MGfilter
         * @access private
         * @param array $filter 过滤条件 必填
         * @param mixed $data 数据 必填
         * @return array
         */
        private function MGFilter(array $filter, mixed $data): array
        {
                $arr = [];
                foreach ($data as $k => $dataValue) {
                        foreach ($filter as $k => $v) {
                                if ($v[1] == "=" or $v[1] == "等于") {
                                        if ($dataValue->{$v[0]} == $v[2]) {
                                                array_push($arr, $dataValue);
                                        }

                                }
                                if ($v[1] == "<" or $v[1] == "小于") {
                                        if ($dataValue->{$v[0]} < $v[2]) {
                                                array_push($arr, $dataValue);
                                        }
                                }
                                if ($v[1] == ">" or $v[1] == "大于") {
                                        if ($dataValue->{$v[0]} > $v[2]) {
                                                array_push($arr, $dataValue);
                                        }
                                }
                                if ($v[1] == "<=" or $v[1] == "小于等于") {
                                        if ($dataValue->{$v[0]} <= $v[2]) {
                                                array_push($arr, $dataValue);
                                        }
                                }
                                if ($v[1] == ">=" or $v[1] == "大于等于") {
                                        if ($dataValue->{$v[0]} >= $v[2]) {
                                                array_push($arr, $dataValue);
                                        }
                                }
                                if ($v[1] == "!=" or $v[1] == "不等于") {
                                        if ($dataValue->{$v[0]} != $v[2]) {
                                                array_push($arr, $dataValue);
                                        }
                                }

                        }
                }
                return $arr;
        }
        /**
         * 查询后先执行
         * @access public
         * @date 2024/04/10
         * @param \Closure $void 执行函数 必填
         * @return \Closure($query) 回调一个带查询后数据的函数
         */
        public function selectAfterVoid(\Closure $void): \Closure
        {
                return $void($this->select());
        }
        /**
         * 查询前先执行
         * @access public
         * @date 2024/04/10
         * @param \Closure $void 执行函数 必填
         * @param bool $defaultSelect 默认开启查询 选填 默认为 false
         * @return mixed
         */
        public function selectBeforeVoid(\Closure $void, bool $defaultSelect = true): mixed
        {
                $void();
                return $defaultSelect ? $this->select() : $this;
        }
        /**
         * 修改单条字段
         * TODO只修改单条数据中的字段值
         */
        public function updateOne(string $field, mixed $value)
        {
                $find = (new MG($this::$CollectionName))->where($this::$filter)->Once();
                if (!$find)
                        return \tank\Error\error::create("没有找到该数据", __FILE__, __LINE__);
                (new MG($this::$CollectionName))->where($this::$filter)->update([$field => $value]);
        }
        /**
         * Guid删除
         * TODO根据模型层Guid来进行删除
         * @param string $Guid Guid值 必填
         */
        public function GuidDelete(string $Guid)
        {
                $find = $this->where([$this::$Guid[0] => $Guid])->Once();
                if (!$find)
                        return \tank\Error\error::create("删除失败!", __FILE__, __LINE__);
                !$this::$Guid ? \tank\Error\error::create("请配置模型层来进行Guid删除!", __FILE__, __LINE__) : $this->where([$this::$Guid[0] => $Guid])->delete();
        }
        /**
         * Key键删除
         * TODO根据模型层的Key键来进行删除
         * @param int $keyNumber Key值 必填
         */
        public function KeyDelete(int $keyNumber)
        {
                $find = $this->where([$this::$Key => $keyNumber])->Once();
                if (!$find)
                        return \tank\Error\error::create("删除失败!", __FILE__, __LINE__);
                !$this::$Key ? \tank\Error\error::create("请配置模型层来进行Key键删除!", __FILE__, __LINE__) : $this->where([$this::$Key => $keyNumber])->delete();
        }
        /**
         * 开启写入业务姓名字段
         * TODO开始进行业务姓名字段的写入
         * @param array $data 数据集 必填
         */
        public function OpenWriteUserNameField(array $data)
        {
                if (!$this::$UserNameWriteField)
                        return $data;
                $data[$this::$UserNameField] = $this::$UserNameFieldValue;
                return $data;
        }
        /**
         * 数值（增减）
         * TODO用来给数值类型的文档来进行修改值
         * @access public
         * @param array|object $data 文档数据 必填
         * @param string $field 字段 必填
         * @param int $number 值 必填
         */
        public function inc(array $data, string $field, int $number)
        {
                $con = [
                        $field => $data[$field] + $number
                ];
                (new MG($this::$CollectionName))->where([$field => $data[$field]])->update($con);
                return $this;
        }
        /**
         * 写入Guid
         * TODO用来自动写入Guid
         * @return array 文档内容数据. 必填
         */
        private function AutoWriteGuid(array $data)
        {
                //*获取Guid后转码
                $GuidValue = Tool::AutomaticID(date("Y-m-d-H.i:s") . $data[$this::$Guid[1]]);
                $data[$this::$Guid[0]] = $GuidValue; //*添加Guid字段
                return $data;
        }
        /**
         * 写入Key键
         * TODO用来自动写入Key键。
         * @return array 文档内容数据. 必填
         */
        private function AutoWriteKey(array $data)
        {
                $length = count((new MG($this::$CollectionName))->find()) + 1; //*计算id
                $data[$this::$Key] = $length; //*添加Key字段
                return $data; //*返回携带添加时间戳后的全部data数据
        }
        /**
         * 去除空字符
         * TODO用来去除其余不必要的字符
         * @access public
         * @param array|string $data 需要去除的内容 必填
         */
        private function DelOtherChar(array|string $data)
        {
                if (is_array($data)) {
                        for ($a = 0; $a < count($data); $a++) {
                                $data[$a] = str_replace("\n", "", $data[$a]);
                                $data[$a] = str_replace("\t", "", $data[$a]);
                                $data[$a] = str_replace(" ", "", $data[$a]);
                        }
                } else {
                        $data = str_replace("\n", "", $data);
                        $data = str_replace("\t", "", $data);
                        $data = str_replace(" ", "", $data);
                }
                return $data;

        }
        /**
         * 连表
         * TODO用来连接其他表中的相同数据。
         * @access public
         * @param string $assOutside 副表 必填
         * @param string $alikeFont 相同字段值 必填
         */
        public function Join(string $assOutside, string $alikeFont)
        {
                //!去除空格
                $alikeFont = $this->DelOtherChar($alikeFont);
                //*裁剪相同字段
                $ArralikeFont = explode("=", $alikeFont);
                //*获取主表的查询
                $mainSelect = $this->use($this::$CollectionName)->where([$ArralikeFont[0] => $ArralikeFont[1]])->select();
                //*获取副表的查询
                $assOutsideSelect = (new MG($assOutside))->where([$ArralikeFont[0] => $ArralikeFont[1]])->select();
                //*循环获取副表相同值
                foreach ($assOutsideSelect as $value) {
                        array_push($mainSelect, $value);
                }
                $this::$isJoin = true; //开启连表
                $this::$JoinValue = $mainSelect;
                $this->documentContent = $this::$JoinValue;
                return $this; //返回实例化
        }
        /**
         * 日志写入
         * TODO用来记载调用的函数的时间和内容。
         * @param string $type 类型
         * @param string $data 写入其余日志内容
         */
        public static function Logs(string $type, string $data)
        {
                Log::TouchRunLog(false, '', getRoot() . "/logs");
                Log::WriteLog(getRoot() . "/logs/RL.log", Log::RunLog($type, $data) . self::$comment . "\n");
        }
        /**
         * 排序
         * TODO用来进行排序操作化
         * @static
         * @param string $fieldName 字段名字 必填
         * @param string $order 排序["asc"->顺序 ,"desc"->倒序] 选填 默认为"asc"
         */
        public function Order(string $fieldName, string $order = "asc")
        {
                self::$order["field"] = $fieldName;
                self::$order["order"] = $order;
                return $this;
        }

        /**
         * MongoDB查询
         * TODO利用MongoDB的函数来查询文档数据内容。
         * @param array $filter 筛选条件 
         */
        public function find(?array $filter = [])
        {

                $this::IsClient();
                $this::Logs("FUNCTION", __FUNCTION__);
                //*获取筛选条件
                $this::$filter = $filter;
                //?判断是否有进行筛选条件
                $isFilter = $this::$filter == [] ? false : true;
                //?没有进行筛选条件则返回所有数据，
                return $isFilter ? $this->where($filter)->select() : $this->select();
        }
        /**
         * 软删除模式
         * TODO用于写入软删除时间戳。
         */
        private function SoftDelete()
        {
                //*获取当前时间戳
                $newDate = Tool::GetNewDate("New");
                self::update([$this::$SoftDeleteField => $newDate]); //写入当前时间戳
        }
        /**
         * 写入其余字段模式
         * @param array $data 新建字段内容
         * TODO用来在新建的基础上添加其余的时间戳。
         * @return array 添加其余实际戳后的新data数据.
         */
        private function OtherWrite(array $data)
        {
                //*获取当前时间戳
                $newDate = Tool::GetNewDate("New");
                //*写入新建时间和修改时间的时间戳
                //?写入字段后面添加新的字段时间戳。
                $data[$this::$OtherWriteField["create"]] = $newDate;
                $data[$this::$OtherWriteField["update"]] = $newDate;
                return $data; //*返回携带添加时间戳后的全部data数据
        }
        /**
         * 写入其余字段模式(修改时间->updatetime)
         */
        private function OtherWriteUpdate(array $data)
        {
                $con = $data;
                //*获取当前时间戳
                $newDate = Tool::GetNewDate("New");
                //*重新写入修改时间的时间戳
                $con[$this::$OtherWriteField["update"]] = $newDate;
                return $con;

        }
        /**
         * 获取数据总数
         * TODO用来直接获取数据的全部个数（总数）
         * @return int 全部的文档数据的总数
         */
        public function Count()
        {
                return $this::$isJoin ? count($this->documentContent) : count($this->documentContent);
        }
        /**
         * 限制长度
         * TODO用来限制Select后获取的总长度
         * @param int $number 长度值
         */
        public function limit(int $number)
        {
                $this->limit = $number;
                return $this;
        }
        /**
         * 跳过长度
         * TODO用来在原有Select后获取的总长度上跳过某些长度
         * @param int $number 长度值
         */
        public function skip(int $number)
        {
                $this->skip = $number;
                return $this;
        }
        /**
         * 强制删除
         * @access public
         * @date 2024-04-11
         */
        public function forcedelete()
        {
                //?判断是否进行过滤筛选
                $isFilter = $this::$filter == [] ? false : true;
                if (!$isFilter)
                        return Tool::abort("请进行过滤筛选！");

                if ($this::$OpenSoftDelete) {
                        $this::$OpenSoftDelete = false;
                        $this->delete();
                        $this::$OpenSoftDelete = true;
                } else {
                        $this->delete();
                }
        }
        /**
         * 删除单条数据
         * TODO用来删除单条文档数据。
         * @param bool $isForce 是否强制删除 选填 默认为 false [模型层]
         */
        public function delete(bool $isForce = false)
        {
                $this::onBeforeDelete(); //*删除前函数

                $isForce ? $this->forcedelete() : null;

                //?判断是否开启软删除
                if (!$this::$OpenSoftDelete) {
                        $this::IsClient();
                        $this::Logs("FUNCTION", __FUNCTION__);
                        $mongoDB = self::$mongoDB;
                        $collection = self::$CollectionName;
                        //?判断是否进行过滤筛选
                        $isFilter = $this::$filter == [] ? false : true;
                        if (!$isFilter)
                                return Tool::abort("请进行过滤筛选！");
                        // 构建更新命令
                        $deleteCommand = new BulkWrite();
                        $deleteCommand->delete($this::$filter);
                        // 执行更新
                        $result = self::$manager->executeBulkWrite("{$mongoDB}.{$collection}", $deleteCommand);
                } else {
                        self::SoftDelete(); //调用软删除
                }
                $this::onAfterDelete(); //*删除后函数

        }
        /**
         * 单条修改数据
         * TODO用来修改单条数据。
         * @param array $update 修改条件
         */
        public function update(array $update)
        {
                $this::onBeforeUpdate(); //*修改前函数
                $this::IsClient();
                $this::Logs("FUNCTION", __FUNCTION__);
                $mongoDB = self::$mongoDB;
                $collection = self::$CollectionName;
                //?判断是否走写入其余字段
                $update = $this::$OpenOtherWriteField == true ? $this->OtherWriteUpdate($update) : $update;
                $this::$update = ['$set' => $update];
                // 构建更新对象-
                $updateOptions = ['multi' => false, 'upsert' => false];
                //?判断是否进行过滤筛选
                $isFilter = $this::$filter == [] ? false : true;
                if (!$isFilter)
                        return Tool::abort("请进行过滤筛选！");
                //?判断是否走软删除
                $this::$OpenSoftDelete ? $this::$filter[$this::$SoftDeleteField] = '' : $this::$filter;
                // 构建更新命令
                $updateCommand = new BulkWrite();
                $updateCommand->update($this::$filter, $this::$update, $updateOptions);
                // 执行更新
                $result = self::$manager->executeBulkWrite("{$mongoDB}.{$collection}", $updateCommand);
                $this::onAfterUpdate(); //*修改后函数

        }
        /**
         * 保存数据|创建数据 
         * *没有值存在的时候则创建数据 否则 修改数据
         * @access public
         * @param array $data 修改或创建的数据 必填
         * @return MG
         */
        public function save(array $data): MG
        {
                //?是否对应值存在
                $find = $this->where([array_keys($data)[0] => array_values($data)[0]])->Once();
                if (!$find)
                        $this->create($data);
                $this->update($data);
                return $this;
        }
        /**
         * 筛选字段
         * TODO用来过滤多余的显示字段数据。
         * @param array $field 筛选字段 必填
         * @return MG
         */
        public function field(array $field): MG
        {
                $arr = [];
                foreach ($field as $k => $v) {
                        $arr[$v] = true;
                }
                $this::$field = $arr;
                return $this; //*返回对象实例
        }
        /**
         * 单条数据
         * @access public
         * TODO用来获取某一个单条文档数据。
         * @return array
         */
        public function Once(): array
        {
                $once = $this->select();
                if (!$once)
                        return [];
                return (array) $once[0];
        }

        /**
         * 查询其余操作验证
         * @return void
         */
        private function SelectOtherControls(): void
        {
                //?判断是否走limit
                if (isset($this->limit) and !isset($this->skip)) {
                        $this->MGLimit();
                }
                //?判断是否走skip
                else if (isset($this->skip) and !isset($this->limit)) {
                        $this->MGSkip();
                }
                //?判断是否走limit和skip
                else if (isset($this->limit) and isset($this->skip)) {
                        $this->MGLimitAndSkip();
                }
        }
        /**
         * 分页
         * TODO用来对列表进行分页操作
         * @access public
         * @param int $page 页数 必填
         * @param int $limit 长度 必填
         * @return self
         */
        public function page(int $page, int $limit): self
        {
                $this->options["limit"] = $limit;
                $this->options["skip"] = ($page - 1) * $limit;
                return $this;
        }
        /**
         * 查询数据
         * TODO用来查询最终结果。
         * @param bool $isGetCount 是否获取数据的总数 选填 默认为 false
         * @return int|array
         */
        public function select(bool $isGetCount = false): int|array
        {
                //*查询前函数
                $this::onBeforeSelect();
                //?判断是否开启软删除
                $filter = $this::$OpenSoftDelete == true ? $this->SelectDelete($this::$filter) : $this::$filter;
                $this::IsClient();
                $this::Logs("FUNCTION", "数据库" . __FUNCTION__ . "|表:{$this::$CollectionName}");
                $mongoDB = self::$mongoDB;
                $collection = self::$CollectionName;

                $this->SelectOtherControls();

                // 构建查询对象
                $query = new Query($filter, array_merge(['projection' => $this::$field], $this->options));
                // 执行查询
                $cursor = self::$manager->executeQuery("{$mongoDB}.{$collection}", $query);
                //?判断是否为空
                if (!isset($cursor))
                        return [];

                $this->documentContent = $cursor->toArray();
                
                //?判断是否走了排序化操作
                if (self::$order != []) {
                        $this->MGOrder();
                }
                //?判断是否连表
                if ($this::$isJoin) {
                        return $isGetCount ? $this->Count() : $this::$JoinValue;
                }

                //?是否启动Tank字典
                if ($this->TankOfDo["useTankDictionary"] != []) {
                        $useDictionary = $this->TankOfDo["useTankDictionary"];
                        if ($useDictionary["type"] == "key") {
                                $dic = $useDictionary["main"]["dic"];
                                $field = $useDictionary["main"]["field"];
                                foreach ($this->documentContent as $k => $v) {
                                        foreach ($field as $field_k => $field_v) {
                                                $v->$field_v = (new \tank\Admin\LocalhostDictionary())->getKey($dic, $v->$field_v);
                                        }
                                }
                        }
                }

                //*查询后函数
                $this::onAfterSelect();
                //*返回最终所有值
                return $isGetCount ? $this->Count() : $this->documentContent;
        }
        /**
         * 软删除查询
         * @param array $filter 筛选条件
         */
        private function SelectDelete(array $filter)
        {
                $filter[$this::$SoftDeleteField] = '';
                return $filter;
        }
        /**
         * MGLimitAndSkip
         * @return void
         */
        private function MGLimitAndSkip(): void
        {
                $this->options["limit"] = $this->limit;
                $this->options["skip"] = $this->skip;
        }
        /**
         * MGSkip
         * @return void
         */
        private function MGSkip(): void
        {
                $this->options["skip"] = $this->skip;
        }
        /**
         * MGlimit
         *@return void
         */
        private function MGLimit(): void
        {
                $this->options["limit"] = $this->limit;
        }
        /**
         * MG排序
         */
        private function MGOrder()
        {
                $con = $this->documentContent; //*重新拿取全部文档
                $sortType = match (self::$order["order"]) {
                        "asc" => SORT_ASC,
                        "desc" => SORT_DESC,
                };
                $sort = array_column($con, self::$order["field"]);
                array_multisort($sort, $sortType, $con);
                $this->documentContent = $con; //*重新返回数据
        }
        /**
         * 筛选条件
         * TODO用来筛选指定需要的文档数据。
         * @param array $filter 筛选条件
         */
        public function where(array $filter)
        {
                $this::IsClient();
                $this::$filter = $filter; //*获取过滤条件
                return $this; //*返回实例化
        }
        /**
         * 注释内容
         * TODO用来添加注释提示。
         * @param string $data 注释内容
         */
        public function comment(string $data)
        {
                $this::IsClient();
                $this::$comment = $data;
                return $this; //*返回实例化
        }

        /**
         * (模型层)创造单条文档数据内容
         * TODO利用模型层的性质来创建单条文档数据内容。
         * @param array $data 新建文档内容数据
         */
        public function Modelcreate(array $data)
        {
                $this::onBeforeCreate(); //*添加前函数
                $this::IsClient();
                $this::Logs("FUNCTION", __FUNCTION__);
                $mongoDB = self::$mongoDB;
                $collection = self::$CollectionName;
                //!排除异常
                if (array_keys($this::$writefield) == null)
                        return Tool::abort("请在{$this->CollectionName}模型层中定义写入字段！");
                if (array_keys($this::$writefield) == [])
                        return Tool::abort("模型层写入字段不能是空集！");
                if (count($data) != count(array_keys($this::$writefield)))
                        return Tool::abort("字段长度不统一！");
                //*转换数据
                $datas = [];

                for ($a = 0; $a < count($data); $a++) {
                        $datas[array_keys($this::$writefield)[$a]] = $data[$a];
                }
                //?判断是否开启自动写入Key键
                $datas = $this::$KeyStatus ? $this->AutoWriteKey($datas) : $datas;
                //?判断是否开启自动写入Guid
                $datas = $this::$GuidStatus ? $this->AutoWriteGuid($datas) : $datas;
                //?判断是否开启写入业务姓名字段
                $datas = $this->OpenWriteUserNameField($datas);
                //?判断是否开启软删除
                $datas = $this::$OpenSoftDelete ? $this->WriteDelete($datas) : $datas;
                //?判断是否开启写入其余字段
                $datas = $this::$OpenOtherWriteField ? $this->OtherWrite($datas) : $datas;
                //*添加数据
                // 创建BulkWrite对象
                $bulk = new BulkWrite();
                $bulk->insert($datas);
                // 执行插入操作(添加数据)
                self::$manager->executeBulkWrite("{$mongoDB}.{$collection}", $bulk);
                $this::onAfterCreate(); //*添加后函数
                return $this;

        }
        /**
         * 软删除写入
         * @param array $data 文档数据
         */
        private function WriteDelete(array $data)
        {
                $data[$this::$SoftDeleteField] = '';
                return $data; //*返回携带添加时间戳后的全部data数据
        }
        /**
         * 多条添加文档数据
         * TODO用来添加多条文档数据内容。
         * @param array $data 添加文档数据
         */
        public function createMany(array $data)
        {
                $this::IsClient();
                $this::Logs("FUNCTION", __FUNCTION__);
                $mongoDB = self::$mongoDB;
                $collection = self::$CollectionName;
                for ($a = 0; $a < count($data); $a++) {
                        //?判断是否开启写入其余字段
                        $data[$a] = $this::$OpenOtherWriteField == true ? $this->OtherWrite($data[$a]) : $data[$a];
                        // 创建BulkWrite对象
                        $bulk = new BulkWrite();
                        $bulk->insert($data[$a]);
                        // 执行插入操作(添加数据)
                        self::$manager->executeBulkWrite("{$mongoDB}.{$collection}", $bulk);
                }
                return $this; //*返回对象实例
        }
        /**
         * 单条多批量创造文档数据
         * TODO用于过多单条数据的添加
         * @param array $data 数据内容
         * @param int $number 批量创造的次数
         */
        public function createBatch(array $data, int $number)
        {
                $this::IsClient();
                $this::Logs("FUNCTION", __FUNCTION__);
                $mongoDB = self::$mongoDB;
                $collection = self::$CollectionName;
                //!异常排除
                if ($number < 0)
                        return Tool::abort(__FUNCTION__ . "长度不能小于0！");
                if ($number > $this::MaxCreateBath)
                        return Tool::abort(__FUNCTION__ . "批量长度过载！");
                //?判断是否开启写入其余字段
                $data = $this::$OpenOtherWriteField == true ? $this->OtherWrite($data) : $data;
                for ($i = 0; $i < $number; $i++) {
                        $this->create($data);
                }
                return $this; //*返回对象实例
        }
        /**
         * 创造单条文档数据
         * TODO同来添加单条文档数据。
         * @param array $data 数据内容
         */
        public function create(array $data)
        {
                $this::IsClient();
                $this::Logs("FUNCTION", __FUNCTION__);
                $mongoDB = self::$mongoDB;
                $collection = self::$CollectionName;
                //?判断是否开启写入其余字段
                $data = $this::$OpenOtherWriteField == true ? $this->OtherWrite($data) : $data;
                // 创建BulkWrite对象
                $bulk = new BulkWrite();
                $bulk->insert($data);
                // 执行插入操作(添加数据)
                self::$manager->executeBulkWrite("{$mongoDB}.{$collection}", $bulk);
                return $this; //*返回对象实例
        }
        /**
         * 选中MongoDB集合
         * TODO用来选择MongoDB集合
         * @param string $collectionName 集合名字
         */
        public function use(string $collectionName)
        {
                $this::IsClient();
                self::$CollectionName = $collectionName;
                return $this; //*返回对象实例
        }

        /**
         * 数据库状态判断
         * TODO用来验证是否连接数据库
         * @static
         */
        public static function IsClient()
        {
                if (!self::$ClientStatus) {
                        Tool::abort("数据库未连接！");
                        return false;
                }
                return true;
        }
        /**
         * 连接MongoDB数据库->(自动连接)
         * TODO连接MongoDB数据库
         * @static
         */
        public static function Connect()
        {
                $host = SQL::$MongoDB['SQLLocalhost'];
                $port = SQL::$MongoDB['SQLPort'];
                $dbName = SQL::$MongoDB['SQLDatabaseName'];
                try {
                        self::$manager = new Manager("mongodb://$host:$port");
                        self::$mongoDB = $dbName;
                        //连接成功
                        self::$ClientStatus = true;
                } catch (DriverException $e) {
                        //连接失败
                        self::$ClientStatus = false;
                        return Tool::abort("连接失败！");
                }
        }
        /**
         * 获取数据库列表
         * @static
         * @return Json
         */
        public static function getSQLList(): mixed
        {
                // 获取数据库列表
                $command = new MongoDBCommand(['listDatabases' => 1]);
                $cursor = self::$manager->executeCommand('admin', $command);
                $databases = current($cursor->toArray())->databases;
                // 输出数据库列表
                foreach ($databases as $database) {
                        return Tool::JSON(['data' => $databases]);
                }
        }
        /**
         * 获取集合列表
         * @static
         * @access public
         * @return array
         */
        public static function getCollectionList(): array
        {
                $data = [];
                $manager = self::$manager;
                $command = new MongoDBCommand(["listCollections" => 1]);
                $cursor = $manager->executeCommand(self::$mongoDB, $command);
                foreach ($cursor as $document) {
                        $data[] = $document;
                }
                return $data;
        }
}