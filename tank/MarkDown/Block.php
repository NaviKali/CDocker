<?php

namespace tank\MarkDown;


/**
 * MarkDownBlock类型
 * @author liulei
 * @date 2024-07-19
 */
class Block
{
    /**
     * 区块定义
     * @access public
     * @param int $index 区块级别 选填 默认为 1
     * @return Block
     */
    public function DefineBlock(int $index = 1): Block
    {
        $str = "";
        for ($i = 0; $i < $index; $i++) {
            $str .= ">";
        }
        MarkDown::$writeData = $str . "\t" . MarkDown::$writeData;
        return $this;
    }

    /**
     * 追加其余样式
     * @access public
     * @param mixed $Type 类型 必填
     * @param string $void 执行函数 必填
     * @param mixed $params 额外参数 选填 默认为 ''
     * @return Block
     */
    public function Append(mixed $Type, string $void, mixed $params = ''): Block
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