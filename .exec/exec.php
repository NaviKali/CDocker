<?php
/**
 * TankExec-Tank服务命令
 */
namespace exec\exec;

require 'autoloadExec.php';

error_reporting(0);

try {
        (new \FromExec\index)->Weclome();
        while (true) {
                echo "请输入命令:";
                $UserRead = readline();    
                $Class = "\\FromExec\\" . explode(":", $UserRead)[0];
                $Function = explode(":", $UserRead)[1];
                (new $Class)->$Function();
        }
} catch (\Exception $e) {
        return $e ;
}