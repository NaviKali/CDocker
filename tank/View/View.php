<?php


namespace tank\View;

use tank\Error\error as Error;
use tank\Request\Request;
use tank\Tool\Tool;
use tank\View\ViewData;
use function tank\Abort;
use function tank\getCurrentUrl;
use function tank\getRoot;

class View
{
        /**
         * 获取视图层配置
         */
        public static $ViewConfig;

        /* 当前页面 */
        public static $view;
        /* 当前渲染页面 */
        public static $viewPage;
        /* 携带参数 */
        public static $params;
        /**
         * 头属性
         */
        public static $HeaderAttr;
        /**
         * 视图层变量
         */
        public static $var = [];
        /**
         * 开启渲染页面
         * @author LL
         * @access public
         * @param string $view 视图层 必填
         * @param array $params 携带参数 选填
         * @param array $attr 头属性 选填
         */
        public static function Start(string $view, array $params = [], array $attr = [])
        {
                self::$ViewConfig = require (getRoot() . "/config/View.php");
                self::$HeaderAttr = $attr;
                $url = getRoot() . "views/";
                self::$view = $view;
                self::$viewPage = $url . $view . ".php";
                //?判断html是否存在
                if (!file_exists(self::$viewPage)) {
                        return Error::create("视图层PHP文件不存在!", __FILE__, __LINE__);
                }
                //*加载参数
                $TK = $params;
                self::$params = $params;
                //*引入渲染页面
                self::IncludeView($params);
                return;
        }
        /**
         * 媒体查询相应式视图层
         * !记住，前端也要进行配合
         * *根据GET的mediaType参数来进行跳转
         * @access public
         * @static
         * @param array ...$view 视图层格式 [[视图名，额外参数{里面必须带有view字段->[width宽度,to跳转]},其余属性]]
         */
        public static function MediaView(array ...$view): void
        {
                if (!isset(Request::param()["mediaType"]))
                        self::Start($view[0][0][0], $view[0][0][1], $view[0][0][2]);
                foreach ($view as $view_k => $view_v) {
                        foreach ($view_v as $k => $v) {
                                if (isset(Request::param()["mediaType"]) and str_replace("_", "/", Request::param()["mediaType"]) == $v[0]) {
                                        self::Start($v[0], $v[1], $v[2]);
                                }
                        }
                }
        }
        /**
         * 引入页面
         */
        private static function IncludeView(array $TK): void
        {

                $HTML_Content = file_get_contents(self::$viewPage);
                /**
                 * 开始头
                 */
                for ($i = 0; $i < count(ViewData::$ViewStartHeaderInclude); $i++) {
                        $HTML_Content = str_replace(ViewData::$ViewStartHeaderInclude[$i], ViewData::$ViewStartHeaderChange[$i], $HTML_Content);
                }
                if (self::$HeaderAttr == []) {
                        $HTML_Content = str_replace("[STYLE]", "", $HTML_Content);
                } else {
                        $HTML_Content = self::HeaderStartAttrChange($HTML_Content, self::$HeaderAttr);
                }
                /**
                 * 静态文件
                 */
                for ($i = 0; $i < count(self::$ViewConfig['StaticFileUrl']); $i++) {
                        $HTML_Content = str_replace(array_keys(self::$ViewConfig['StaticFileUrl'])[$i], array_values(self::$ViewConfig['StaticFileUrl'])[$i], $HTML_Content);
                }
                /**
                 * 自定义标签
                 */
                for ($i = 0; $i < count(self::$ViewConfig['CustomLabel']); $i++) {
                        $HTML_Content = str_replace(array_keys(self::$ViewConfig['CustomLabel'])[$i], array_values(self::$ViewConfig['CustomLabel'])[$i], $HTML_Content);
                }

                /**
                 * 页面切换
                 */
                for ($i = 0; $i < count(ViewData::$ViewInclude); $i++) {
                        $HTML_Content = str_replace(ViewData::$ViewInclude[$i], ViewData::$ViewChange[$i], $HTML_Content);
                }
                /**
                 * 页面属性切换
                 */
                for ($i = 0; $i < count(ViewData::$StyleInclude); $i++) {
                        $HTML_Content = str_replace(ViewData::$StyleInclude[$i], ViewData::$StyleChange[$i], $HTML_Content);
                }
                /**
                 * 函数标签切换
                 */
                for ($i = 0; $i < count(ViewData::$FunctionTagInclude); $i++) {
                        $HTML_Content = str_replace(ViewData::$FunctionTagInclude[$i], ViewData::$FunctionTagChange[$i], $HTML_Content);
                }

                Tool::WriteFile(getRoot() . "public/then/view.html", $HTML_Content);
                include (getRoot() . "public/then/view.html");
        }
        /**
         * 开始头属性修改
         */
        private static function HeaderStartAttrChange(string $data, array $attr)
        {
                $str = "";
                if (in_array("title", array_keys($attr)))
                        $data = str_replace("[DOCUMENT]", $attr["title"], $data);
                if (in_array("auto-refresh", array_keys($attr)))
                        $str .= '<meta http-equiv="refresh" content="' . $attr["auto-refresh"] . '">' . "\n" . '';
                if (in_array("author", array_keys($attr)))
                        $str .= '<meta name="author" content="' . $attr["author"] . '">' . "\n" . '';
                if (in_array("description", array_keys($attr)))
                        $str .= '<meta name="description" content="' . $attr["description"] . '">' . "\n" . '';
                if (in_array("keywords", array_keys($attr)))
                        $str .= '<meta name="keywords" content="' . $attr["keywords"] . '">' . "\n" . '';
                if (in_array("base", array_keys($attr)))
                        $str .= '<base href="' . $attr["base"] . '" target="_blank">' . "\n" . '';

                $data = str_replace("[STYLE]", $str, $data);

                return $data;
        }
        /**
         * 获取当前级别页面
         * @author LL
         * @access public
         */
        public static function getlevelPage(): int
        {
                $pageArr = explode("\\", self::$view);
                return count($pageArr);
        }
        /**
         * For循环
         * @access private 
         * @param string $data 循环内容 必填
         * @param int $length 循环长度 选填 默认为 5
         * @return void
         */
        private static function TFor(string $data, int $length = 5): void
        {
                for ($i = 0; $i < $length; $i++) {
                        echo $data;
                }
        }
        /**
         * If判断
         * @access private
         * @param mixed $data 判断某一个值 必填
         * @param string $true true时，输出
         * @param string $false fasle时，输出
         */
        private static function TIf(mixed $data, string $true, string $false): void
        {
                if (!empty($data)) {
                        echo $true;
                } else {
                        echo $false;
                }
        }
        /**
         * 创造一个变量
         * @access private
         * @param string $key 变量键 必填
         * @param mixed $value 变量值 必填
         * @return void
         */
        private static function var(string $key, mixed $value)
        {
                self::$var[$key] = $value;
        }
        /**
         * 获取变量
         * @access private
         * @param string $key 变量键 必填
         * @param string $isReturn 是否返回 选填 默认为 false
         */
        private static function get(string $key, bool $isReturn = false)
        {
                if (!$isReturn) {
                        if (in_array($key, array_keys(self::$var)))
                                echo self::$var[$key];
                } else {
                        return self::$var[$key];
                }
        }
}
