<?php

namespace tank\Param;

use tank\Error\httpError;
use tank\Request\Request;

/**
 * 参数验证器
 */
class Param
{
    /**
     *请求参数
     */
    public array $params;

    public function __construct(string $type)
    {
        $this->params = $type == 'GET' ? Request::param() : ($type == 'POST' ? Request::postparam() : []);
    }

    /**
     * 根据具体自定义的可以来判断传入的参数是否存在
     * @param array $keys key键 必填
     * @return self
     * @throws httpError
     */
    public function AccordKeyVerParams(array $keys): self
    {
        foreach ($keys as $k => $v) {
            if (!in_array($v, array_keys($this->params))) {
                throw new \tank\error\httpError("{$v}参数没被传入！");
            }
        }
        return $this;
    }

    /**
     * 根据模型层来判断是否传入对应参数
     * @param mixed $model 模型层 必填
     * @param bool $isGetModelField 是否只获取模型层字段 选填 默认为 false
     * @return array
     * @throws httpError
     */
    public function ModelVerParams(mixed $model, bool $isGetModelField = false): array
    {
        $data = [];
        $param = $this->params;
        $keys = array_keys($model::$writefield);
        foreach ($keys as $k => $v) {
            if (!in_array($v, array_keys($param))) {
                throw new \tank\error\httpError("{$v}参数没被传入！");
            }
            $data[$v] = $param[$v];
        }
        if ($isGetModelField) {
            return $data;
        } else {
            return $param;
        }
    }
}