<?php
namespace tank\SQL;

use config\SQL;
use mysqli;
use tank\Attribute\Attribute;

class MySQL
{
        /**数据库实例 */
        public static mysqli $NewSQL;
        /**SQLQuery */
        public static string $SelectQuery = "SELECT * FROM UseTable";
        /**SQLWhere */
        public static string $WhereQuery = '';
        /**
         * Delete子句删除
         */
        public function delete(): void
        {
                $WhereQuery = $this::$WhereQuery;
                $this->Query("DELETE FROM $this->table $WhereQuery");
        }
        /**
         * Update子句修改
         * @access public
         * @param array $data 数据 必填
         * @return void
         */
        public function update(array $data): void
        {
                $con = $this->ArrayToSring($data);
                $WhereQuery = $this::$WhereQuery;
                $this->Query("UPDATE $this->table SET $con  $WhereQuery");
        }
        /**
         * 数组转换
         * @access public 
         * @param array $data 需要转为字符串的数组 必填
         * @param string $string 中间字符 选填 默认为 ,
         * @return string
         */
        private function ArrayToSring(array $data, string $string = ','): string
        {
                $con = [];
                $keys = array_keys($data);
                $values = array_values($data);
                for ($i = 0; $i < count($data); $i++) {
                        array_push($con, "$keys[$i] = '$values[$i]'");
                }
                $con = join("$string", $con);
                return $con;
        }
        /**
         * Where子句筛选
         * @access public
         * @param string|array $data 筛选内容 必填
         * @param string $type 筛选类型 选填 默认为AND
         * @return \tank\SQL\MySQL
         */
        public function where(string|array $data, string $type = "AND"): \tank\SQL\MySQL
        {
                (new Attribute("FUNCTION", "Where子句筛选,请根据需求筛选需要的数据内容。"));
                if (is_string($data)) {
                        $value = explode("=", $data);
                        $this::$WhereQuery = "\tWHERE\t$value[0] = '$value[1]'";
                }
                if (is_array($data)) {
                        $con = $this->ArrayToSring($data, "\t$type\t");
                        $this::$WhereQuery = "\tWHERE\t$con";
                }
                $this::$SelectQuery = $this::$SelectQuery . $this::$WhereQuery;
                return $this;
        }
        /**
         * 查询数据
         * @access public
         * @return array 
         */
        public function select(): array
        {
                (new Attribute("FUNCTION", "MySQL数据库的表查询"));
                $select = [];
                $Query = str_replace('UseTable', $this->table, $this::$SelectQuery);
                foreach ($this::$NewSQL->query($Query) as $key => $value) {
                        array_push($select, $value);
                }
                return $select;
        }
        /**
         * 添加数据
         * TODO添加一条数据
         * @access public
         * @param array $data 数据 必填
         * @return void
         */
        public function create(array $data): void
        {
                (new Attribute("FUNCTION", "MySQL数据库添加一条数据/只能一条!"));
                $fielts = join(",", array_keys($data));
                $values = [];
                for ($i = 0; $i < count(array_values($data)); $i++) {
                        array_push($values, '"' . array_values($data)[$i] . '",');
                }
                $values = join("", $values);
                $values = substr($values, 0, strlen($values) - 1);
                $this->Query("INSERT INTO $this->table ( $fielts ) VALUES ( $values );");
        }
        /**
         * 选择表
         * @access public
         * @param string $table 表 必填
         * @return \tank\SQL\MySQL
         */
        public function use (string $table): \tank\SQL\MySQL
        {
                (new Attribute("FUNCTION", "选择表/切换表"));
                $this->table = $table;
                return $this;
        }
        /**
         * MySQL构造函数
         * @access public
         * @param string $table 表 选填 默认为""
         */
        public function __construct(public string $table/**数据表选择 */ = "")
        {
        }
        /**
         * 数据库连接
         * TODOMySQL数据库的连接。
         * @return void
         */
        public static function Connect(): void
        {
                (new Attribute("FUNCTION", "MySQL数据库连接"));
                try {
                        $conn = new mysqli(SQL::$MySQL['SQLLocalhost'], SQL::$MySQL['MySQL_User'], SQL::$MySQL['MySQL_PassWord'], SQL::$MySQL['SQLDatabaseName'], SQL::$MySQL['SQLPort']);
                        self::$NewSQL = $conn;
                } catch (\Exception $e) {
                        var_dump($e);
                        die;
                }
        }
        /**
         * 原生MySQL命令
         * TODO使用原生MySQL命令。
         * @access public
         * @param string $query 命令内容 必填
         * @return bool|\tank\Error\error
         */
        public function Query(string $query): bool|\tank\Error\error
        {
                (new Attribute("FUNCTION", "执行MySQL原生查询命令操作"));
                try {
                        return $this::$NewSQL->query($query) ? true : \tank\Error\error::create("SQL执行错误!", __FILE__, __LINE__);
                } catch (\Exception $e) {
                        \tank\Error\error::create($e, __FILE__, __LINE__);
                        return false;
                }
        }
        /**
         * 创建表
         * TODO快速创建数据表
         * @access public
         * @param string $tableName 数据表名字 必填
         * @param string|array $tableFile 数据表字段 必填
         * @return \tank\SQL\MySQL
         */
        public function CreateTable(string $tableName, string|array $tableFile): \tank\SQL\MySQL
        {
                (new Attribute("FUCNTION", "创建一个数据表"));
                $file = is_string($tableFile) ? $tableFile : implode(',', $tableFile);
                $this->Query("CREATE TABLE $tableName (
                        $file
                );");
                return $this;
        }
}
