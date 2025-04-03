<?php

namespace tank\MarkDown;

/**
 * MarkDownParagraphs类型
 * @author liulei
 * @date 2024-07-19
 */
class Paragraphs
{
    /**
     * 字体样式
     * @access public
     * @param string $type 类型 选填 默认为 italics
     * @type italics 斜体
     * @type bold 粗体
     * @type all 粗斜体
     * @return Paragraphs
     */
    public function FontStyle(string $type = "italics"): Paragraphs
    {
        if ($type == "italics")
            MarkDown::$writeData = "*" . MarkDown::$writeData . "*";
        if ($type == "bold")
            MarkDown::$writeData = "**" . MarkDown::$writeData . "**";
        if ($type == "all")
            MarkDown::$writeData = "***" . MarkDown::$writeData . "***";
        return $this;
    }
    /**
     * 分割线
     * @access public
     * @return Paragraphs
     */
    public function PartingLine(): Paragraphs
    {
        MarkDown::$writeData = MarkDown::$writeData . PHP_EOL . "***" . PHP_EOL;
        return $this;
    }
    /**
     * 删除线
     * @access public
     * @return Paragraphs
     */
    public function StrikeThrough(): Paragraphs
    {
        MarkDown::$writeData = "~~" . MarkDown::$writeData . "~~";
        return $this;
    }
    /**
     * 下划线
     * @access public
     * @return Paragraphs
     */
    public function UnderLined(): Paragraphs
    {
        MarkDown::$writeData = "<u>" . MarkDown::$writeData . "<u>";
        return $this;
    }
    /**
     * 脚注
     * @access public
     * @param string $data 注解内容 必填 [注解名称，注解内容]
     * @return Paragraphs
     */
    public function Footer(array $data): Paragraphs
    {
        MarkDown::$writeData = MarkDown::$writeData ."\t". "[$data[0]]"."\n"."[$data[0]]:".$data[1];
        return $this;
    }
    /**
     * 追加其余样式
     * @access public
     * @param mixed $Type 类型 必填
     * @param string $void 执行函数 必填
     * @param mixed $params 额外参数 选填 默认为 ''
     * @return Paragraphs
     */
    public function Append(mixed $Type, string $void, mixed $params = ''): Paragraphs
    {
        $Class = '\tank\MarkDown' . "\\" . $Type;
        (new $Class(MarkDown::$writeData))->$void();
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