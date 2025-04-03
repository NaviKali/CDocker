<?php
/**
 * PHP驱动
 * !如果问题需要修改 Drive.env 环境变量
 */
namespace tank\Drive;

use tank\Env\env;
use tank\Tool\Tool;
use tank\Attribute\Attribute;

class Drive
{
    /**
     * 当前使用PHP的配置文件路径
     */
    public string $CurrentUsePHPiniFileUrl;
    /**
     * 驱动列表
     */
    public array $DriveList = [];
    /**
     * 获取配置文件
     * !如果问题注意环境变量
     */
    public function __construct()
    {
        $this->CurrentUsePHPiniFileUrl = (new env("Drive"))->get("PHPUrl") . (new env("Drive"))->get("PHPini");
        $this->iniFileisExiste();
        $this->iniFileHandle(Tool::FileRead($this->CurrentUsePHPiniFileUrl));
    }
    /**
     * 获取驱动列表
     * @access public
     * @date 2024/3/28
     * @param string $type 驱动类型 选填 默认为 start  [(开启的驱动)start,(关闭的驱动)close]
     * @return array
     */
    public function getDriveList(string $type = "start"): array
    {
        return $this->DriveList[$type];
    }
    /**
     * 配置文件是否存在
     * @date 2024/3/28
     * @access protected
     * @return void
     */
    protected function iniFileisExiste(): void
    {
        if (!file_exists($this->CurrentUsePHPiniFileUrl))
            \tank\Error\error::create("配置文件路径错误!", __FILE__, __LINE__);
    }
    /**
     * 配置文件处理
     * @date 2024/3/28
     * @access protected
     * @param string $file 配置文件内容 必填
     * @return void
     */
    protected function iniFileHandle(string $file): void
    {
        $file = explode(PHP_EOL, $file);
        $start = [];
        $close = [];
        foreach ($file as $k => $v) {
            //* 开启的驱动
            if (str_starts_with($v, "extension=")) {
                array_push($start, trim(str_replace("extension=", "", $v)));
            }
            //* 关闭的驱动
            else if (str_starts_with($v, ";extension=")) {
                array_push($close, trim(str_replace("extension=", "", $v)));
            }
        }
        /**
         * 关闭的驱动处理
         */
        $closeHandle = [];
        foreach ($close as $k => $v) {
            array_push($closeHandle, explode(";", $v)[1]);
        }
        $this->DriveList = ["start" => $start, "close" => $closeHandle];
    }
    /**
     * 是否存在该某一个驱动
     * @access public
     * @date 2024/3/28
     * @param string $drivename 驱动名字 必填
     * @return mixed
     */
    public function Has(string $drivename): mixed
    {
        return in_array($drivename, $this->DriveList['start']) ? true : (in_array($drivename, $this->DriveList['close']) ? true : false);
    }
    /**
     * 是否开启某一个或某一些驱动
     * @access public
     * @date 2024/3/28
     * @param string|array $drivename 驱动名字 必填
     * @return bool|array
     */
    public function IsOpenDrive(string|array $drivename): bool|array
    {
        if (is_string($drivename))
            return in_array($drivename, $this->DriveList["start"]) ? true : false;

        $arr = [];
        if (is_array($drivename)) {
            foreach ($drivename as $k => $v) {
                $arr[$v] = in_array($v, $this->DriveList["start"]) ? true : false;
            }
            return $arr;
        }
        return true;
    }
}