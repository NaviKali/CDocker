<?php
/**
 * 本地字典
 */

namespace tank\Admin;

use tank\Env\env;
use tank\Tool\Tool;
use function tank\getRoot;

class LocalhostDictionary
{
    /**
     * 字典读取后缀名
     */
    protected string $Suffix = "dic";
    /**
     * 指定存放目录
     */
    protected string $DesignationDir;
    /**
     * 所有字段键值对
     */
    protected array $dictionaryContent;
    /**
     * 构造本地字典
     * @access public
     */
    public function __construct()
    {
        $this->DesignationDir = (new env("LocalhostDictionary"))->get("DesignationDir");

        //?判断当前目录下是否存在对应字典目录文件夹
        $isHas = $this->isHasDesignationDir();

        //*没有指定目录则生成
        if (!$isHas)
            mkdir(getRoot() . $this->DesignationDir);

        $this->dictionaryContent = $this->ReadAllFileDic();
    }
    /**
     * 获取自定值
     * @access public
     * @date 2024-5-7
     * @param string $key 键 必填
     * @return mixed
     */
    public function getDesignationValue(string $key): mixed
    {
        return $this->dictionaryContent[$key];
    }
    /**
     * 获取所有键值对数据
     * @access public
     * @date 2024-5-7
     * @return array
     */
    public function getAllDictionary(): array
    {
        return $this->dictionaryContent;
    }
    /**
     * 普遍读取每个dic文件下的所有字典内容
     * @access private
     * @return array
     */
    private function ReadAllFileDic(): array
    {
        $arr = [];
        foreach (glob(getRoot() . $this->DesignationDir . "/*.{$this->Suffix}") as $k => $v) {
            foreach (file($v) as $k => $filevalue) {
                array_push($arr, $filevalue);
            }
        }
        $valueArr = [];
        foreach ($arr as $k => $v) {
            $valueArr[explode(":", $v)[0]] = explode(":", $v)[1];
        }
        return $valueArr;
    }
    /**
     * 读取某一个dic字典里面所有字典内容
     * @access public
     * @param string $filename dic文件名称 必填
     * @return array
     */
    public function ReadFileDic(string $filename): array
    {
        $arr = [];
        foreach (file(getRoot() . "dictionary/$filename.dic") as $k => $filevalue) {
            array_push($arr, $filevalue);
        }
        $valueArr = [];
        foreach ($arr as $k => $v) {
            $valueArr[explode(":", $v)[0]] = explode(":", $v)[1];
        }
        return $valueArr;
    }
    /**
     * 是否存在指定目录
     * @access private
     * @return bool
     */
    private function isHasDesignationDir(): bool
    {
        if (file_exists(getRoot() . $this->DesignationDir) and is_dir(getRoot() . $this->DesignationDir)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 获取某一个dic字典文件下面的key
     * @access public
     * @param string $dicname dic文件名 必填
     * @param string $value 值 必填
     * @return string
     */
    public function getKey(string $dicname, string $value): string
    {
        $arr = $this->ReadFileDic($dicname);
        $key = '';
        foreach ($arr as $k => $v) {
            if ($v == $value)
                $key = $k;
        }
        return $key;
    }

}