<?php
namespace tank\Admin;

use tank\Env\env;
use tank\Tool\Tool;
use function tank\getRoot;

/**
 * 本地数据库
 * TODO主要用来实习后端接口的预做而产生的，先用本地数据库来构造一个数据库表来测试。
 */

class LocalhostSql
{
    /**
     * 数据文件后缀
     */
    protected string $DataFileSuffix = "tql";
    /**
     * 完整路径
     */
    protected string $SQLUrl;
    /**
     * 数据库文件夹名称
     */
    protected string $SQLDirName;
    /**
     * 表名
     */
    protected string $table;
    /**
     * 定义字段
     */
    protected string $defineField;
    /**
     * 查询数据
     */
    protected array $Document;
    /**
     * 是否转数组
     */
    protected bool $isToArray = false;
    /**
     * 筛选条件
     */
    protected array $where = [];
    /**
     * 构造数据库
     */
    public function __construct()
    {
        $this->SQLDirName = (new env("LocalhostSql"))->get("SQLDir");

        $isHas = $this->isHasDesignationDir();
        if (!$isHas)
            mkdir(getRoot() . $this->SQLDirName);

        $this->SQLUrl = getRoot() . $this->SQLDirName . "/";
    }
    /**
     * 转数组
     * @access public
     * @return LocalhostSql
     */
    public function toArray(): LocalhostSql
    {
        $this->isToArray = true;
        return $this;
    }
    /**
     * 查询数据
     * @access public
     * @return array
     */
    public function select(): array
    {
        $data = Tool::FileRead($this->SQLUrl . "/" . $this->table . "." . $this->DataFileSuffix);
        $data = substr($data, 0, strlen($data) - 2);
        $data = explode(".", $data);
        $arr = [];
        foreach ($data as $k => $v) {
            if ($this->isToArray) {
                array_push($arr, (array) json_decode(substr($v, 0, strlen($v) - 3) . "\n}"));
            } else {
                array_push($arr, json_decode(substr($v, 0, strlen($v) - 3) . "\n}"));
            }
        }
        $this->Document = $arr;

        return $arr;
    }
    /**
     * 添加数据
     * @access public
     * @return void
     */
    public function create():void
    {
        Tool::AutomaticFile($this->SQLUrl, $this->table, $this->DataFileSuffix, $this->defineField, "a");
    }
    /**
     * 定义 数据字段
     * @access public
     * @param array $field 字段+值 必填 键值对
     */
    public function defineField(array $field): LocalhostSql
    {
        $str = "";
        foreach ($field as $k => $v) {
            $str .= "\t" . '"' . $k . '"' . ":" . '"' . $v . '",' . "\n";
        }
        $this->defineField = "{\n" . $str . "}.\n";
        return $this;
    }
    /**
     * 创建 / 使用 表
     * @access public
     * @param string $table 表名 必填
     * @return LocalhostSql
     */
    public function use(string $table): LocalhostSql
    {
        $this->table = $table;

        Tool::AutomaticFile($this->SQLUrl, $table, $this->DataFileSuffix);

        return $this;
    }
    /**
     * 是否存在指定目录
     * @access private
     * @return bool
     */
    private function isHasDesignationDir(): bool
    {
        if (file_exists(getRoot() . $this->SQLDirName) and is_dir(getRoot() . $this->SQLDirName)) {
            return true;
        } else {
            return false;
        }
    }
}