<?php
namespace tank\Excel;

use PHPExcel;
use tank\Error\httpError;
use tank\Tool\Tool;


class Excel
{
    /**
     * 支持类型
     */
    public array $SupportType = [
        "csv"
    ];
    /**
     * 文件名字
     */
    protected string $filename;
    /**
     * 生成目录路径
     */
    protected string $catalogueUrl;
    /**
     * 第三方Excel类
     */
    public \Vtiful\Kernel\Excel $ClassExcel;
    /**
     * Excel配置Config
     */
    public array $ExcelConfig = [];
    /**
     * Excel头部标题
     */
    public array $ExcelHeader = [];
    /**
     * Excel第一单元模块名字
     */
    public string $ExcelFirstSheetName;
    /**
     * Excel数据写入
     */
    public array $ExcelData;
    /**
     * 构造验证
     * @date 2024-04-19
     * @access public
     * @param string $filename 文件名字 必填
     * @param string $catalogueUrl 生成到某一个目录下面 必填
     * @throws httpError
     */
    public function __construct(string $filename, string $catalogueUrl)
    {
        $this->filename = $filename;
        $this->catalogueUrl = $catalogueUrl;
        $this->ExcelConfig = ['path' => $catalogueUrl];
        // $this->ClassExcel = (new \Vtiful\Kernel\Excel($this->ExcelConfig));
    }

    /**
     * 获取支持生成文件类型
     * @access public
     * @date 2024-04-19
     */
    public function getSupportMakeExeclFileType()
    {
        return $this->SupportType;
    }
    /**
     * 生成CSV表格文件
     * @access public
     * @param array $header 标题头 必填
     * @param array $data 内容数据 必填 二维数组
     * @return void
     */
    public function csv(array $header, array $data): void
    {
        $file = fopen($this->catalogueUrl . "/{$this->filename}." . __FUNCTION__, 'w');
        fputcsv($file, $header);

        if (\tank\MG\Operate::RecognitionArrayDimension($data) != 2)
            throw new httpError("生成文件参数问题!");

        foreach ($data as $k => $v) {
            fputcsv($file, $v);
        }

        fclose($file);
    }
    /**
     * 配置Excel标题头
     * @access public
     * @param array $header 头 必填
     * @return Excel
     */
    public function ExcelHeader(array $header): Excel
    {
        $this->ExcelHeader = $header;
        return $this;
    }
    /**
     * 设置Excel第一单元模块名字
     * @access public
     * @param string $name 名字 必填
     * @return Excel
     */
    public function ExcelFirstSheetName(string $name): Excel
    {
        $this->ExcelFirstSheetName = $name;
        return $this;
    }
    /**
     * 配置Excel数据写入
     * @access public
     * @param array $data 写入数据 必填 二维数组
     * @return Excel
     */
    public function ExcelData(array $data): Excel
    {
        if (\tank\MG\Operate::RecognitionArrayDimension($data) != 2)
            throw new httpError("生成文件参数问题!");
        $this->ExcelData = $data;
        return $this;
    }
    /**
     * 生成Excel文件格式 | 生成Excel
     * @access public
     * !维护
     */
    public function xlsx()
    {
    }




}