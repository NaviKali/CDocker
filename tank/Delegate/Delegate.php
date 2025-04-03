<?php

namespace tank\Delegate;

use function tank\getRoot;

/**
 * 委托(Delegate)
 */
class Delegate
{
        /**
         * 执行委托
         * @access public
         * @author LL
         * @param string $NameSpace 命名空间 必填
         * @param string $Class 类名 必填
         * @param string $functionName 函数名字 选填 默认为空
         * @param \Closure $functionBefore 委托前函数 必填
         * @param \Closure $functionAfter 委托后函数 必填
         */
        public function __construct(string $NameSpace, string $Class, string $functionName = null, \Closure $functionBefore, \Closure $functionAfter)
        {
                $functionBefore();
                $DelegateFunction = $functionName;
                $Url = $NameSpace . "\\" . $Class;
                $DelegateFunction == null ? (new $Url) : (new $Url)->$DelegateFunction();
                $functionAfter();
        }
}