<?php

namespace tank\MarkDown;

use tank\MarkDown\Title;
use tank\MarkDown\CList;
use tank\MarkDown\Paragraphs;
use tank\MarkDown\Block;
use tank\MarkDown\Code;

/**
 * MarkDown类
 * @author liulei
 * @date 2024-07-19
 */
class MarkDown
{
    /**
     * 写入行内容
     * @access public
     * @var string
     */
    public static string $writeData = "";
    /**
     * 生成行类型
     * @access public
     * @var string
     */
    public string $makeType;
    /**
     * 类型转换实体类
     * @access protected
     * @var array
     */
    protected array $ToChangeNewClass = [
        "Title" => Title::class,
        "Paragraphs" => Paragraphs::class,
        "List" => CList::class,
        "Block" => Block::class,
        "Code" => Code::class,
    ];
    /**
     * 生成最终内容
     * @access protected
     * @static
     * @var string
     */
    public static string $makeEndData = "";
    /**
     * 构造MarkDown
     * @access public
     * @param string $type 类型 必填
     * @param string $writeData 写入内容 必填
     * @type Title 标题类型
     * @type Paragraphs 段落类型
     * @type List 列表类型
     * @type Block 区块类型
     */
    public function __construct(string $type, string $writeData)
    {
        $this->makeType = $type;
        self::$writeData = $writeData;
    }
    /**
     * 执行对应类型
     * @access public
     * @return mixed
     */
    public function RunType(): mixed
    {
        return (new $this->ToChangeNewClass[$this->makeType](self::$writeData));
    }
}