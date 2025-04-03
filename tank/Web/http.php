<?php

namespace tank\Web;

use tank\Error\error as Error;
use tank\Attribute\Attribute;
use tank\Func\Func;
use tank\MG\MG;
use tank\Request\Request;
use tank\Tool\Tool;

class http
{
        /**
         * 实例化APP路径
         */
        public static $AppUrl;
        /**
         * 命名值
         */
        public static $NameSpace;
        /**
         * APP应用路径
         */
        public static $AppServerUrl;
        /**
         * 伪名函数
         */
        public static $Mount = [];
        /**
         * 构造APP协议
         * @access public
         * @param string $Dir App路径 必填 只需要填写__FILE即可。
         */
        public function __construct(string $Dir)
        {
                $this::$AppUrl = $Dir;
        }
        /**
         * (挂载)伪名函数
         * @access public
         * @param array $data 定义挂载的伪名函数 选填
         * @return \tank\Web\http 实例体
         */
        final public function Mount(array $data = []): \tank\Web\http
        {
                (new Attribute('FUNCTION', "伪名挂载类似于函数域名，利用键值对的特性来取代原来的路径。"));
                $this::$Mount = $data;
                return $this;
        }
        /**
         * 实施APP协议
         * @author LL
         * @access public
         * @return \tank\web\http 当前类实例体
         */
        final public function App(): \tank\Web\http
        {
                (new Attribute("FUNCTION", "验证APP文件是否存在!"));
                $this->getNameSpace();
                $this->DirExists();
                return $this;
        }
        /**
         * 连接APP协议
         * @author LL
         * @access public
         */
        final public function Client()
        {
                (new Attribute("FUNCTION", "连接APP文件，然后就可以实现自动化接口。", [
                        "getAPPFile" => 2,
                        "getAPPFunction" => 1,
                ]));
                //*获取特性
                $Attr = Attribute::getAttribute();
                $httpUrl = \tank\Func\Func::getUrl();
                $Arr_httpUrl = explode("/", $httpUrl);
                //*获取App文件
                $App_file = $Arr_httpUrl[count($Arr_httpUrl) - $Attr['other']['getAPPFile']];
                //*获取调用函数
                $App_function = $Arr_httpUrl[count($Arr_httpUrl) - $Attr['other']['getAPPFunction']];
                //!防止出现GET请求的错误
                $App_function = explode("?", $App_function);
                $App_function = $App_function[0];

                /**
                 *  ?是否为伪名函数
                 */
                if (array_key_exists($App_function, $this::$Mount)) {
                        /**
                         * 获取对应APP类 | 调用对应函数
                         */
                        $class = explode('/', $this::$Mount[$App_function])[0];
                        //!异常错误处理
                        $this::ErrorHandle($class);
                        $App_function = explode('/', $this::$Mount[$App_function])[1];
                        $Class = $this::getCorrespondAppClass($class);
                } else {
                        /**
                         * 如果没有挂载 -> 那么就需要具体类加函数 | 获取对应APP类
                         */
                        //!异常错误处理
                        $this::ErrorHandle($App_file);
                        $Class = $this::getCorrespondAppClass($App_file);
                }
                //调用对应函数
                echo ((new $Class())->$App_function((new Request), (new MG())));

        }
        /**
         * 错误异常处理
         */
        public static function ErrorHandle(string $AppFile)
        {
                if (!file_exists(str_replace("\\", "/", self::$AppServerUrl . "\\controller\\{$AppFile}.php"))) {
                        Error::create("App文件不存在！", __FILE__, __LINE__);
                        return;
                }
        }
        /**
         * 获取对应APP类
         * @access public
         * @static
         * @param string $className 类名
         */
        public static function getCorrespondAppClass($className)
        {
                return "\app\\" . self::$NameSpace . "\controller\\{$className}";
        }
        /**
         * 获取NameSpace命名值
         * @author LL
         * @access private
         */
        private function getNameSpace()
        {
                $url = $this->DelOtherUrl(__DIR__);
                $url = str_replace("{$url}public/app/", "", $this::$AppUrl);
                $NameSpace = str_replace(".php", "", $url); //*获取主要的值
                $this::$NameSpace = $NameSpace;
                return $NameSpace;
        }
        /**
         * 目录是否存在
         * @author LL
         * @access private
         */
        private function DirExists()
        {
                $dir = $this::$AppUrl;
                $dir = str_replace("public/app/", "", $dir);
                $dir = str_replace(".php", "", $dir);
                $dir = str_replace($this::$NameSpace, "", $dir);
                $dir = "{$dir}app/{$this::$NameSpace}";
                if (!file_exists($dir)) {
                        Error::create("App协议错误！", __FILE__, __LINE__);
                        return;
                }
                $this::$AppServerUrl = $dir; //*获取App应用路径

        }
        /**
         * 截取多余路径
         * @author LL
         * @access private
         * @param string $Dir 路径 必填
         */
        private function DelOtherUrl(string $Dir)
        {
                $Dir = str_replace(str_replace("\\", "/", __NAMESPACE__), "", $Dir);
                return $Dir;
        }

        /**
         * 路由虚幻
         * TODO生成随机GET请求参数[障眼法]
         * @return string
         */
        public static function RouterUnreal(): string
        {
                $before = self::MakeRouterParams(isStartUnreal: true);
                return $before;
        }
        /**
         * 路由后缀参数生成
         * @access public
         * @param array $data 参数+值 键值对 选填
         * @param bool $isStartUnreal 是否开启虚幻模式 选填 默认为 false
         * @return string
         */
        public static function MakeRouterParams(array $data = [], bool $isStartUnreal = false): string
        {
                $str = '';
                if (!$isStartUnreal) {

                        foreach ($data as $k => $v) {
                                $str .= "$k=$v&";
                        }
                } else {

                        $length = rand(5, 10);
                        $str = Tool::Salt(time());
                        $url = "";
                        for ($i = 0; $i < $length; $i++) {
                                $randomNumber = rand(1, 20);
                                $keyStart = rand(1, $randomNumber);
                                $valueStart = rand(6, 15);
                                $StartStr = substr($str, $keyStart, $valueStart);
                                $keyEnd = rand(1, $randomNumber);
                                $valueEnd = rand(6, 15);
                                $EndStr = substr($str, $keyEnd, $valueEnd);
                                $url .= "$StartStr=$EndStr&";
                        }
                }

                $end = $isStartUnreal ? $url . "isStartUnreal=true" : substr($str, 0, strlen($str) - 1);
                return "?" . $end;

        }

}