<?php

namespace tank\MarkDown;


/**
 * MarkDownTitle类型
 * @author liulei
 * @date 2024-07-19
 */
class Title
{
    /**
     * 定义标题
     * @access public
     * @param int $level 级别 选填 默认为 1
     * @return Title
     */
    public function DefineTitle(int $level = 1): Title
    {
        $str = "";
        for ($i = 0; $i < $level; $i++) {
            $str .= "#";
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
     * @return Title
     */
    public function Append(mixed $Type, string $void, mixed $params = ''): Title
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