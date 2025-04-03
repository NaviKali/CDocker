<?php

#|----------------------------------|
#|       WECLOME TANK               |
#|     I LIKE PHP FROM THIS         |
#|      @email 2149573631@qq.com    |
#|----------------------------------|


namespace config\Base;

use tank\MG\MG;


use function tank\SQLConnect;
use function tank\OverallSituationErrorConfig;

/**
 * Base基础引入PHP文件
 */
//*实例化Base
$base = new Base();
//*获取前缀路径
//*获取路径名字
$Url = __DIR__;
//*路径截取
$Url = str_replace("config", "", $Url);
// $Url = $base->baseUrl . $base->Name;
//* 引入文件集合 ↓


/**
 * Tank配置
 */
$Tank = [
        "$Url/tank/Cookie/Cookie.php",
        "$Url/tank/Error/error.php",
        "$Url/tank/Log/Log.php",
        "$Url/tank/MG/MG.php",
        "$Url/tank/MD/MD.php",
        "$Url/tank/Func/Func.php",
        "$Url/tank/MG/Operate.php",
        "$Url/tank/Request/Request.php",
        "$Url/tank/Tool/Tool.php",
        "$Url/tank/Upload/Upload.php",
        "$Url/tank/View/View.php",
        "$Url/tank/View/ViewData.php",
        "$Url/tank/BaseController.php",
        "$Url/tank/tank.php",
        "$Url/tank/Attribute/Attribute.php",
        "$Url/tank/Session/Session.php",
        "$Url/tank/Delegate/Delegate.php",
        "$Url/tank/Command/Command.php",
        "$Url/tank/SQL/MySQL.php",
        "$Url/tank/App/App.php",
        "$Url/tank/Web/http.php",
        "$Url/tank/VerificationCode/VerificationCode.php",
        "$Url/tank/Env/env.php",
        "$Url/tank/Drive/Drive.php",
        "$Url/tank/Excel/Excel.php",
        "$Url/tank/View/Dom.php",
        "$Url/tank/Admin/LocalhostAdmin.php",
        "$Url/tank/Admin/LocalhostDictionary.php",
        "$Url/tank/Admin/LocalhostSql.php",
        "$Url/tank/Env/systemenv.php",
        "$Url/tank/ProcessFlow/FlowVoid.php",
        "$Url/tank/ProcessFlow/FlowDocker.php",
        "$Url/tank/ProcessFlow/Flow.php",
        "$Url/tank/MarkDown/MarkDown.php",
        "$Url/tank/MarkDown/Title.php",
        "$Url/tank/MarkDown/Paragraphs.php",
        "$Url/tank/MarkDown/List.php",
        "$Url/tank/MarkDown/Block.php",
        "$Url/tank/MarkDown/Code.php",
];
/**
 * 错误异常
 */
$Error = [
        "$Url/tank/Error/httpError.php",
        "$Url/tank/Error/phpversionError.php",
        "$Url/tank/Error/uploadfiletypeError.php",
];
/**
 * 配置引入
 */
$Config = [
        "$Url/config/Header.php",
        "$Url/config/Code.php",
        "$Url/config/Middleware.php",
        "$Url/config/Model.php",
        "$Url/config/ConstLanguage.php",
];



/**
 * 基础层
 */
class Base
{
        /**
         * 自动引入文件PHP
         * TODO用来自动识别加载文件并且自动include引入到Base基层中。
         * @param string  $Url
         */
        public static function AutomaticInclude(string $Url)
        {
                $file_list = glob($Url . "/*.php");
                foreach ($file_list as $file) {
                        $file = str_replace("\\", "/", $file);
                        (new Base())->BatchInclude($file);
                }
        }


        /**
         * !批量includePHP文件
         * TODO 用来批量引入PHP文件，方便省时间。
         * @param string|array $url 访问PHP文件地址
         * @return mixed
         */
        public function BatchInclude(string|array $url)
        {
                $this->IsExists($url);
                if (is_string($url)) {
                        include ($url);
                }
                if (is_array($url)) {
                        for ($i = 0; $i < count($url); $i++) {
                                include ($url[$i]);
                        }
                }
        }
        /**
         * !批量验证PHP文件是否存在
         * TODO用来判断PHP文件是否存在。
         * @param string|array $url 访问PHP文件地址
         * @return mixed
         */
        private static function IsExists(string|array $url)
        {
                if (is_string($url)) {
                        if (!file_exists($url))
                                trigger_error("$url.PHP文件不存在！", E_USER_ERROR);
                }
                if (is_array($url)) {
                        for ($i = 0; $i < count($url); $i++) {
                                if (!file_exists($url[$i]))
                                        trigger_error("$url[$i].PHP文件不存在！", E_USER_ERROR);
                        }
                }
        }
}
/**
 * *自动引入common/interface 接口类
 */
(new Base())->AutomaticInclude($Url . "app/common/interface");


//*引入PHP文件
$base->BatchInclude($Tank);
$base->BatchInclude($Error);
$base->BatchInclude($Config);

/**
 * *自动引入void函数层
 */
(new Base())->AutomaticInclude($Url . "app/void");


/**
 * 全局错误
 */
OverallSituationErrorConfig();

/**
 * !调用连接数据库
 */
// SQLConnect();
