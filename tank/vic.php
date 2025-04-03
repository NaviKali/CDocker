<?php
/**
 * 快捷服务-vic
 */
namespace tank;

class vic
{
    /**
     * 服务列表
     * @access public
     * @var array
     */
    public array $serviceArr = [
        // "service" => "php -S localhost:8080 -t public"
    ];
    /**
     * 构造服务
     * @access public
     * @param string $serviceName 服务名称 must
     */
    public function __construct(public string $serviceName)
    {
    }
    /**
     * 运行服务
     * @access public
     * @return void
     */
    public function run(): void
    {
        $this->VerIsHasService();

        exec($this->serviceArr[$this->serviceName]);
    }
    /**
     * 重新定义快捷服务列表
     * @access public
     * @param array $service
     * @return self
     */
    public function resetServiceArr(array $service): self
    {
        $this->serviceArr = $service;
        return $this;
    }
    /**
     * 验证服务是否存在
     * @access private
     * @return void
     */
    private function VerIsHasService(): void
    {
        if (!in_array($this->serviceName, array_keys($this->serviceArr))) {
            echo ("服务不存在!");
            die;
        }
    }



}