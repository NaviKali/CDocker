<?php

namespace tank\ProcessFlow;


#|----------------------------------|
#|       WECLOME TANK               |
#|     I LIKE PHP FROM THIS         |
#|      @email 2149573631@qq.com    |
#|----------------------------------|
#|          流 程 操 作              |
#|----------------------------------|
/**
 * @see title 终端标题 string
 * @see in 进入路径 string
 * @see cd 进入路径 string
 * @see start 启动程序 string
 * @see deletefile 删除文件 string
 * @see deleteoc 删除文件夹 string
 * @see mkdir 生成文件夹 string
 * @see @see 注解 string
 * @see @@ 注解 string
 */



use tank\ProcessFlow\FlowVoid;
use tank\Tool\Tool;

use function tank\getRoot;

class Flow extends FlowVoid
{
    /**
     * 读取文件夹
     * @var string
     */
    protected string $ReadOS = ".do";

    /**
     * 文件后缀
     * @var string
     */
    protected string $Suffix = "flow";

    /**
     * 流程名称
     * @var string
     */
    protected string $FlowName;

    /**
     * 流程内容
     */
    protected mixed $FlowData;

    /**
     * 流程语言
     */
    protected array $FlowLanguage =
    [
        "title" => "ToTitle",
        "in" => "ToCd",
        "cd" => "ToCd",
        "start" => "ToStart",
        "deletefile"=>"ToDeleteFile",
        "deleteos"=>"ToDeleteOs",
        "mkdir"=>"ToMkdir",
    ];

    /**
     * 特殊特征
     */
    protected array $SpecialLanguage =
    [
        "@see" => "ToSee",
        "@rem" => "ToRem",
        "@@" => "ToSee",
    ];

    /**
     * 构造
     * @access public
     * @param string $name 流程名称 选填 默认为 ''
     */

    public function __construct(string $name = '')
    {
        $this->FlowName = $name;
        $this->ReadFile();
    }

    /**
     * 读取流程内容
     * @access public
     * @return void
     */
    protected function ReadFile(): void
    {
        $this->FlowData = json_decode(Tool::FileRead(getRoot() . '.do/' . $this->FlowName . '.json'));
    }

    /**
     * 定义名称
     * @access public
     * @param string $name 名称 必填
     * @return Flow
     */
    public function SetFlowName(string $name): Flow
    {
        $this->FlowName = $name;
        $this->ReadFile();
        return $this;
    }

    /**
     * 获取文件内容
     * @access public
     * @return string
     */
    public function getFileContent(): string
    {
        return Tool::FileRead(getRoot() . '.do/' . $this->FlowName . '.json');
    }

    /**
     * 启动流程
     * @access public
     * @param bool $Open 是否打开 默认为 true
     * @return void
     */
    public function Run(bool $Open = true): void
    {
        if (!empty($name))
            $this->FlowName = $name;

        $this->StartRun($this->FlowName, $this->DecodeFindData($this->FlowData, array_merge($this->FlowLanguage, $this->SpecialLanguage)), $Open);
    }
}
