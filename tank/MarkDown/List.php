<?php

namespace tank\MarkDown;


/**
 * MarkDownList类型
 * @author liulei
 * @date 2024-07-19
 */
class CList
{
    /**
     * 列表数据
     * @access public
     * @param mixed $data 数据内容 必填
     * @param mixed $type 列表类型 选填 默认为 ul [ul=>无序列表,ol=>有序列表]
     * @return CList
     */
    public function DefineList(mixed $data, string $type = "ul"): CList
    {
        $field = $type == "ul" ? "*" : ($type == "ol" ? "." : "");

        MarkDown::$writeData .= "\n";
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if ($type == "ul")
                    MarkDown::$writeData .= $field . "\t" . $v . "\n";
                if ($type == "ol")
                    MarkDown::$writeData .= $k . "\t" . $field . $v . "\n";
            }
        }
        if (is_string($data)) {
            if ($type == "ul")
                MarkDown::$writeData .= $field . "\t" . $data . "\n";
            if ($type == "ol")
                MarkDown::$writeData .= "1.\t" . $field . "\t" . $data . "\n";
        }
        return $this;
    }

    /**
     * 追加其余样式
     * @access public
     * @param mixed $Type 类型 必填
     * @param string $void 执行函数 必填
     * @param mixed $params 额外参数 选填 默认为 ''
     * @return CList
     */
    public function Append(mixed $Type, string $void, mixed $params = ''): CList
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