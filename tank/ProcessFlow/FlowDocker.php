<?php

namespace tank\ProcessFlow;

use tank\ProcessFlow\FlowVoid;


#|----------------------------------|
#|       WECLOME TANK               |
#|     I LIKE PHP FROM THIS         |
#|      @email 2149573631@qq.com    |
#|----------------------------------|
#|          流 程 操 作(Docker)      |
#|----------------------------------|
/**
 * @see type 类型 string
 * @see i bool
 * @see t bool
 * @see d bool
 * @see env 环境变量 object
 * @see name 别名 string
 * @see images 镜像/容器名 string
 */


class FlowDocker
{
    /**
     * 生成语句
     */
    public string $MakeData = "docker\t";
    /**
     * Docker语言
     */
    protected array $DockerLanguage =
    [
        "type" => "ToType",
        "i" => "ToI",
        "t" => "ToT",
        "d" => "ToD",
        "env" => "ToEnv",
        "name" => "ToName",
        "images" => "ToImages",
    ];


    /**
     * 开启Docker
     * @access public
     * @param array $data 流程内容 必填
     */
    public function Run(array $data)
    {
        $data = (array)$data;
        foreach ($data as $k => $v) {
            if (in_array($k, array_keys($this->DockerLanguage))) {
                $void = $this->DockerLanguage[$k];
                $this->$void($v);
            }
        }

        return $this->MakeData;
    }
    /**
     * 类型转换
     * @access protected
     * @param string $data 数据 必填
     * @return void
     */
    protected function ToType(string $data): void
    {
        switch ($data) {
            case 'run':
                $this->MakeData .= "run\t";
                break;
            case 'stop':
                $this->MakeData .= "stop $data\t";
                break;
            case 'rm_controller':
                $this->MakeData .= "rm $data\t";
                break;
            case 'rm_images':
                $this->MakeData .= "rm -i $data\t";
                break;
            default:
                break;
        }
    }
    /**
     * 转换环境变量
     * @access protected
     * @param mixed $data 数据 必填
     */
    protected function ToEnv(mixed $data)
    {
        $data = (array)$data;
        $str = "-e\t";
        foreach ($data as $k => $v) {
            $str .= "$k=$v\t";
        }
        $this->MakeData .= "$str\t";
    }
    /**
     * 转换镜像
     * @access protected
     * @param string $data 数据 必填
     * @return void
     */
    protected function ToImages(string $data): void
    {
        $this->MakeData .= "$data\t";
    }

    /**
     * 转换别名
     * @access public
     * @param string $data 数据 必填
     * @return void
     */
    protected function ToName(string $data): void
    {
        $this->MakeData .= "--name " . $data . "\t";
    }

    /**
     * 转换i
     * @access protected
     * @param string $data 数据 必填
     * @return void
     */
    protected function ToI(string $data): void
    {
        $this->MakeData .= "-i\t";
    }

    /**
     * 转换t
     * @access protected
     * @param string $data 数据 必填
     * @return void
     */
    protected function ToT(string $data): void
    {
        $this->MakeData .= "-t\t";
    }

    /**
     * 转换d
     * @access protected
     * @param string $data 数据 必填
     * @return void
     */
    protected function ToD(string $data): void
    {
        $this->MakeData .= "-d\t";
    }

    /**
     * 验证是否启动Docker
     * @access public
     * @param array $data 流程内容 必填
     * @return bool
     */
    public static function VerStartDocker(array $data): bool
    {
        if (isset($data['docker']))
            return true;
        return false;
    }
}
