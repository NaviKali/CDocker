<?php
namespace tank\Log\Log;

include('../../config/Logs.php');
use tank\Tool\Tool as Tool;
use config\Logs as ConfigLogs;

/**
 * 日志
 * @author LL
 */
class Log
{
        /**
         * 日志写入
         * @param string $url 日志路径
         * @param string $data 写入日志的内容
         * TODO用来直接将日志内容写进日志内。
         */
        public static function WriteLog(string $url, string $data)
        {
                $write = Tool::FileWrite($url, $data);
                return Tool::Message(200, '写入完成！');

        }
        /**
         * 日志->(记录每次执行的方法和时间->在当前时间执行一次?方法)[需要搭配一键生成日志或生成代码执行!]
         * @author L
         * @access public
         * @param string $type 日志类型-必填
         * @param string $Configdata 日志相关配置-选填
         * @param bool $isStrict 是否严格写入
         * @test 类型:FUNCTION->__FUNCTION__;CLASS->__CLASS__;INFORMATION->根据自己需求;
         * @todo 根据汇报执行了哪些日志信息，可以是个函数也可以是个类也可以是段信息。
         */
        public static function RunLog(string $type, string $Configdata, bool $isStrict = true): string
        {
                $isStrict = ConfigLogs::$Log['strict']; //!~是否为严格模式 - 匹配Config配置项
                $type = Tool::NotNull($type);
                $time = Tool::GetNewDate('New');
                if ($isStrict) {
                        switch ($type) {
                                case "FUNCTION":
                                        $data = "[" . $time . "][FUNCTION]你执行了一次-" . $Configdata . "\n ";
                                        return $data;
                                case "CLASS":
                                        $data = "[" . $time . "][CLASS]调试一次-" . $Configdata . "\n";
                                        return $data;
                                case "INFORMATION":
                                        $data = "[" . $time . "][INFORMATION]相关信息-" . $Configdata . "\n";
                                        return $data;
                                default:
                                        $data = "[" . $time . "][NONE]默认-" . $Configdata . "\n";
                                        return $data;
                        }
                }
                if (!$isStrict) {
                        $data = "[" . $time . "]" . $Configdata . "\n";
                }

        }
        /**
         * 一键生成日志->(自动生成日志文件夹(可选)和文件(必选))
         * @author L
         * @access public
         * @param bool $ChoiseOs 选择是否生成日志文件夹ChoiseOs-选填
         * @param string $OsSrc 生成日志文件夹路径OsSrc-选填
         * @param string $FileSrc 生成日志文件路径FileSrc-必填
         * @todo 生成日志的文件夹与文件
         */
        public static function TouchRunLog(bool $ChoiseOs = false, string $OsSrc = "", string $FileSrc = "")
        {
                try {
                        $value = $FileSrc == "" ? 1 : 2;
                        switch ($value) {
                                case 1:
                                        $value = Tool::msg(404, "生成日志路径-必填!");
                                        return $value;
                                case 2:
                                        $OsName = "runlog"; //日志文件夹名字(可以修改)
                                        if ($ChoiseOs) {
                                                $value = Tool::AutomaticOs($OsSrc, $OsName);
                                                $time = Tool::GetNewDate('Year');
                                                $value = Tool::AutomaticOs($OsSrc . '/' . $OsName, $time);
                                                $newtime = Tool::GetNewDate('Year');
                                                $value = Tool::AutomaticFile($OsSrc . '/' . $OsName . '/' . $time, $newtime, ConfigLogs::$Log['suffix']);
                                                $value = Tool::msg(200, "生成成功!");
                                                return $value;
                                        }
                                        if (!$ChoiseOs) {
                                                $OsSrc = null;
                                                $value = Tool::AutomaticFile($FileSrc, "RL", ConfigLogs::$Log['suffix'], "");
                                                $value = Tool::msg(200, "生成成功!");
                                                return $value;
                                        }
                                        break;
                        }
                } catch (\Exception $e) {
                        return trigger_error("生成失败！"); 
                }
        }

}