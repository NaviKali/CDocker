<?php
namespace config;
use tank\Tool\Tool as Tool;
/**
 * (配置)Code参数
 */
class Code
{
        const Request_Success = 0;
        const Request_Error = 500;
        const Request_Wrong = 404;
        /**
         * Message->Code返回参数
         * @access public
         * @author ll
         * @param int $code 状态代码code->必填
         * @param string $msg 返回内容msg->必填
         * @param array $data 返回数据data->选填
         * @param string $func 返回调用的方法->选填
         * TODO 多样式返回Message接口
         * @return mixed
         */
        public static function Code(int $code, string $msg, array|object $data, ?string $func):mixed
        {
             //*当前时间
             $Date = "20" . Tool::GetNewDate("New");
             //*获取总数
             $Count = count($data);
             //*获取ip地址
             $IP = Tool::GetIP();
             //*获取主机名字
             $LocationName = gethostname();
             $_code =
                  [
                       //*0->申请成功
                       0 => Tool::JSON(["code" => $code, "static" => "申请成功！", "date" => $Date, "count" => $Count, "function" => $func, "PHP[version]" => phpversion(), "IP" => $IP, "hostName" => $LocationName]),
                       //*200->请求成功
                       200 => Tool::JSON(["code" => $code, "msg" => $msg, "data" => $data, "static" => "请求成功！", "date" => $Date, "count" => $Count, "function" => $func, "PHP[version]" => phpversion(), "IP" => $IP, "hostName" => $LocationName]),
                       //*404->请求超时
                       404 => Tool::JSON(["code" => $code, "msg" => $msg, "static" => "请求超时！", "date" => $Date, "count" => $Count, "function" => $func, "PHP[version]" => phpversion(), "IP" => $IP, "hostName" => $LocationName]),
                       //*500->请求失败
                       500 => Tool::JSON(["code" => $code, "static" => "请求失败！", "date" => $Date, "count" => $Count, "function" => $func, "PHP[version]" => phpversion(), "IP" => $IP, "hostName" => $LocationName]),
                       //*400->文件上传失败
                       400 => Tool::JSON(["code" => $code, "msg" => $msg, 'static' => '文件上传失败！', "date" => $Date, "count" => $Count, "function" => $func, "PHP[version]" => phpversion(), "IP" => $IP, "hostName" => $LocationName]),
                       //*401->添加失败
                       401 => Tool::JSON(["code" => $code, "msg" => $msg, "static" => "添加失败！", "date" => $Date, "count" => $Count, "function" => $func, "PHP[version]" => phpversion(), "IP" => $IP, "hostName" => $LocationName]),
                       //*402->编辑失败
                       402 => Tool::JSON(["code" => $code, "msg" => $msg, "static" => "编辑失败！", "date" => $Date, "count" => $Count, "function" => $func, "PHP[version]" => phpversion(), "IP" => $IP, "hostName" => $LocationName]),
                       //*403->删除失败
                       403 => Tool::JSON(["code" => $code, "msg" => $msg, "static" => "删除失败！", "date" => $Date, "count" => $Count, "function" => $func, "PHP[version]" => phpversion(), "IP" => $IP, "hostName" => $LocationName]),
                       //*444->无法找到
                       444 => Tool::JSON(["code" => $code, "static" => "无法找到！", "date" => $Date, "count" => $Count, "function" => $func, "PHP[version]" => phpversion(), "IP" => $IP, "hostName" => $LocationName]),
                  ];
             return $_code[$code];
        }


}