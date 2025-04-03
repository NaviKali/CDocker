<?php

namespace tank\View;

use DOMDocument;
use tank\Error\httpError;

/**
 * HTMLDOM操作
 * @author LL
 */
class Dom
{
    /**
     * HTML文件
     */
    public string $HtmlFile;
    /**
     * DOM体
     */
    public DOMDocument $DOM;
    /**
     * 标签属性名
     */
    public string $AttributeName;
    /**
     * 选择标签元素
     */
    public string $SelectTagElement;
    /**
     * 选择元素索引
     */
    public int|null $SelectIndex = null;
    /**
     * 是否直接获取标签元素内容
     */
    public bool $isGetText = false;
    /**
     * 构造DOM
     * @access public
     * @param string $htmlfile html文件 必填
     */
    public function __construct(string $htmlfile)
    {
        $this->HtmlFile = $htmlfile;
        $this->DOM = (new DOMDocument);
    }
    /**
     * 获取标签元素
     * @access public
     * @param string $tag 标签 必填 
     * @param ?int $index 元素索引 选填 默认为 null 可为Null
     * @return object|string|array
     */
    public function getTagElement(string $tag, ?int $index = null): object|string|array
    {
        $this->DOM->loadHTML(file_get_contents($this->HtmlFile));
        $dom = $this->DOM->getElementsByTagName($tag)->length == 0 ? [] : $this->DOM->getElementsByTagName($tag);

        if (isset($index)) {
            $dom = $dom->item($index);
            if (isset($this->AttributeName))
                return $dom->getAttribute($this->AttributeName);
        }
        ;
        if ($this->isGetText)
            $dom = $dom->textContent;

        return $dom;
    }
    /**
     * 获取引入(src)
     * @access public
     * @param string $tag 标签名 必填
     * @return array
     */
    public function getSrc(string $tag): array
    {
        $arr = [];
        for ($i = 0; $i < $this->getTagElement($tag)->length; $i++) {
            array_push($arr, $this->getAttribute('src')->getTagElement($tag, $i));
        }
        return $arr;
    }
    /**
     * 获取Script引入路径
     * @access public
     * @return array
     */
    public function getSrcOfScript(): array
    {
        return $this->getSrc('script');
    }
    /**
     * 获取标签链接(href)
     * @access public
     * @param string $tag 标签名 必填
     * @return array
     */
    public function getLink(string $tag): array
    {
        $arr = [];
        for ($i = 0; $i < $this->getTagElement($tag)->length; $i++) {
            array_push($arr, $this->getAttribute('href')->getTagElement($tag, $i));
        }
        return $arr;
    }
    /**
     * 获取标签属性
     * @access public
     * @param string $AttributeName 属性名 必填
     * @return Dom
     */
    public function getAttribute(string $AttributeName): Dom
    {
        $this->AttributeName = $AttributeName;
        return $this;
    }
    /**
     * 选择标签元素
     * @access public
     * @param string $tag 标签 必填
     * @return Dom
     */
    public function selectTagElement(string $tag): Dom
    {
        $this->SelectTagElement = $tag;
        return $this;
    }
    /**
     * 选择元素索引
     * @access public
     * @param int $index 索引 必填
     * @return Dom
     */
    public function selectIndex(int $index): Dom
    {
        $this->SelectIndex = $index;
        return $this;
    }
    /**
     * 查询标签元素
     * @access public
     * @return object|string
     */
    public function select(): object|string
    {
        return $this->getTagElement($this->SelectTagElement, $this->SelectIndex);
    }
    /**
     * 获取标签元素内容
     * @access public
     * @return Dom
     */
    public function getText(): Dom
    {
        $this->isGetText = true;
        return $this;
    }
    /**
     * 获取所有a标签链接
     * @access public
     * @return array
     */
    public function getLinkOfA(): array
    {
        return $this->getLink('a');
    }

    /**
     * 插入标签元素
     * @access public
     * @param string $tag 标签名 必填
     * @param string $data 写入数据 必填
     * @return string 插入后当前html文档数据
     */
    public function insert(string $tag, string $data): string
    {
        $dom = $this->getTagElement($this->SelectTagElement, $this->SelectIndex);
        $Newdom = $this->DOM->createElement($tag);
        $Newdom->textContent = $data;

        $dom->appendChild($Newdom);

        return $this->getHTML();
    }
    /**
     * 获取HTML码
     */
    public function getHTML(): string
    {
        return $this->DOM->saveHTML();
    }
    /**
     * 批量插入标签元素
     * !维护
     * @access public
     * @throws httpError
     * @param array $tag 标签名 必填
     * @param array $data 写入数据 必填
     * @return string 插入后当前html文档数据
     */
    public function Batchinsert(array $tag, array $data): string
    {
        if (count($tag) != count($data))
            throw new httpError("长度不统一!");
        foreach ($tag as $k => $v) {
            $this->insert($tag[$k], $data[$k]);
        }
        return $this->getHTML();
    }
}