<?php

namespace tank\ProcessFlow;

use tank\Tool\Tool;
use tank\ProcessFlow\FlowDocker;

use function tank\getRoot;

abstract class FlowVoid
{
    /**
     * 生成执行内容
     */
    protected array|string $MakeData;
    /**
     * 定义 and 运行 - 流程
     * @access protected
     * @param string $name 启动名称 必填
     * @param mixed $data 流程内容 必填
     * @param bool $open 是否打开 必填
     * @return void
     */
    final protected function StartRun(string $name, mixed $data, bool $open): void
    {
        $this->MakeDataChange();

        //?是否启用Docker
        if (FlowDocker::VerStartDocker((array)$data)) {
            $this->MakeData .=  (new FlowDocker)->Run((array)$data["docker"]);
        }

        Tool::FileWrite(getRoot() . time() . ".bat", $this->MakeData);

        if ($open)
            exec("start " . getRoot() . time() . ".bat");
    }

    /**
     * 生成内容转换
     * @access protected
     * @return void
     */
    final protected function MakeDataChange(): void
    {
        $this->MakeData = implode($this->MakeData);
    }

    /**
     * 内容解析排查
     * @access protected
     * @param mixed $data 流程内容 必填
     * @param array $where 排查 必填
     */
    final protected function DecodeFindData(mixed $data, array $where)
    {
        $data = (array)$data;
        foreach ($data as $k => $v) {
            if (in_array($k, array_keys($where))) {
                $void = $where[$k];
                $this->$void($v);
            }
        }
        return $data;
    }

    /**
     * 转换title
     * @access protected
     * @param string $data 数据 必填
     * @return void
     */
    final protected function ToTitle(string $data): void
    {
        $this->MakeData[] = 'title ' . $data . PHP_EOL;
    }

    /**
     * 转换cd
     * @access protected
     * @param string $data 数据 必填
     * @return void
     */
    final protected function ToCd(string $data): void
    {
        $this->MakeData[] = 'cd ' . $data . PHP_EOL;
    }

    /**
     * 转换查看
     * @access protected
     * @param string $data 数据 必填
     * @return void
     */
    final protected function ToSee(string $data): void
    {
        $this->MakeData[] = '@SEE ' . $data . PHP_EOL;
    }

    /**
     * 转换注释
     * @access protected
     * @param string $data 数据 必填
     * @return void
     */
    final protected function ToRem(string $data): void
    {
        $this->MakeData[] = '@REM ' . $data . PHP_EOL;
    }
    /**
     * 删除文件
     * @access protected
     * @param string $data 数据 必填
     * @return void
     */
    final protected function ToDeleteFile(string $data): void
    {
        $this->MakeData[] = "del " . $data . PHP_EOL;
    }
    /**
     * 删除文件夹
     * @access protected
     * @param string $data 数据 必填
     * @return void
     */
    final protected function ToDeleteOs(string $data): void
    {
        $this->MakeData[] = "rd " . $data . PHP_EOL;
    }
    /**
     * 生成文件夹
     * @access protected
     * @param string $data 数据 必填
     * @return void
     */
    final protected function ToMkdir(string $data): void
    {
        $this->MakeData[] = "md " . $data . PHP_EOL;
    }
}
