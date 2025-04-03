<?php
/**
 * 本地管理员
 */
namespace tank\Admin;

use tank\Env\env;
use tank\Tool\Tool;
use function tank\getRoot;

class LocalhostAdmin
{
    /**
     * 本地管理员文件路径
     */
    protected string $LocalhostAdminFileUrl;
    /**
     * 本地管理员配置
     */
    protected array $LocalhostAdminConfig;
    /**
     * 构造
     */
    public function __construct()
    {
        $this->LocalhostAdminFileUrl = getRoot() . (new env("LocalhostAdmin"))->get("MakeLocalhostAdminFile");
        $this->LocalhostAdminConfig = require_once (getRoot() . "config/LocalhostAdmin.php");
        //*生成本地管理员文件
        if (!file_exists($this->LocalhostAdminFileUrl))
            Tool::AutomaticFile(getRoot(), (new env("LocalhostAdmin"))->get("MakeLocalhostAdminFile"));
    }
    /**
     * 创建管理员账号
     * @access public
     * @date 2024/4/1
     * @param string $user 账号 必填
     * @param string $password 密码 必填
     * @return void
     */
    public function CreateAdmin(string $user, string $password):void
    {
        //?不能超出本地管理最大长度
        if (count($this->getLocalhostAdminList()) > $this->LocalhostAdminConfig["LocalhostAdminMaxLimit"])
            return;

        if ($this->LocalhostAdminConfig["Base64"]) {
            $user = base64_encode($user);
            $password = base64_encode($password);
        }
        Tool::FileWrite($this->LocalhostAdminFileUrl, "$user|{$password}[ADMIN]\n");
    }
    /**
     * 获取本地管理员列表
     * @access public
     * @date 2024/4/1
     * @return array
     */
    public function getLocalhostAdminList():array
    {
        $content = Tool::FileRead($this->LocalhostAdminFileUrl);
        return explode("[ADMIN]", $content);
    }
    /**
     * 本地管理员登录
     * @access public
     * @date 2024/4/1
     * @param string $user 账号 必填
     * @param string $password 密码 必填
     * @return bool
     */
    public function LocalhostAdminLogin(string $user, string $password): bool
    {
        if ($this->LocalhostAdminConfig["Base64"]) {
            $user = base64_encode($user);
            $password = base64_encode($password);
        }
        foreach ($this->getLocalhostAdminList() as $k => $v) {
            if ("$user|$password" == $v)
                return true;
            else
                return false;
        }
        return true;
    }


}