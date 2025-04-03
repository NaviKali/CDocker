<?php
namespace app\common\interface;

interface Dictionary
{
    /**
     * 新建字典
     */
    public function CreateDictionary();
    /**
     * 获取字典菜单树
     */
    public function getDictionaryTree();
}