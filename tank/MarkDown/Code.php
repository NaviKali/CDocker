<?php

namespace tank\MarkDown;

/**
 * MarkDownCode类型
 * @author liulei
 * @date 2024-07-19
 */
class Code
{
    /**
     * 定义代码区域
     * @access public
     * @param string $type 指定代码解析 必填
     * @return Code
     */
    public function DefineCode(string $type): Code
    {
        MarkDown::$writeData = "```$type\n" . MarkDown::$writeData . "\n```";
        return $this;
    }
    /**
     * 追加其余样式
     * @access public
     * @param mixed $Type 类型 必填
     * @param string $void 执行函数 必填
     * @param mixed $params 额外参数 选填 默认为 ''
     * @return Code
     */
    public function Append(mixed $Type, string $void, mixed $params = ''): Code
    {
        $Class = '\tank\MarkDown' . "\\" . $Type;
        (new $Class(MarkDown::$writeData))->$void($params);
        return $this;
    }
    /**
     * 结束
     * @access public
     * @return void
     */
    public function End(): void
    {
        MarkDown::$makeEndData .= MarkDown::$writeData . "\n";
    }
}