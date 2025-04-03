<?php


namespace tank\Tool;

use config\Code as CodeConfig;
use tank\Error\error as Error;

/**
 * 封装工具类[(工具)](Tool.php)
 * @access public
 * @author LL
 * @file Tool.php
 * @version 6.0
 * @data 2023.6.26
 * @name Tool 工具类
 * @test 工具类里面包含很多有意思的方法(例如:基本方法、对应前端的方法、对应后端的方法、对应数据库的方法)~:)
 * todo @Vipe By L
 * ?如有调整，请自行修改!
 */
class Tool
{
        /**
         * 基本变量值->方便获取(不调用方法的情况下直接获取)
         * @author L
         * @access public
         */
        /**小皮根路径 */
        public static string $PHP_Study_Root = "C:\phpstudy_pro\WWW/";
        /**Tool工具类的类路径 */
        public static $Tool_CLASS = __CLASS__;
        /**Tool工具类的根路径 */
        public static $Tool__FILE = __FILE__;
        /**Tool工具类的命名空间 */
        public static $Tool_NAMESPACE = __NAMESPACE__;
        /**
         * 生成合同
         * @static
         * @param array $wh 宽和高 必填
         * @param string $title 标题 必填
         * @param string $content 内容 必填
         */
        public static function MakeContract(array $wh, string $title, string $content)
        {
                header("Content-type: image/jpg");
                $img = imagecreatetruecolor($wh[0], $wh[1]);
                $backgroundColor = imagecolorallocate($img, 255, 255, 255);
                $textColor = imagecolorallocate($img, 0, 0, 0);
                imagefill($img, 0, 0, $backgroundColor);
                imagestring($img, 10, 20, 20, $title, $textColor);
                imagestring($img, 2, 40, 60, $content, $textColor);
                imagestring($img, 2, $wh[0] - 200, $wh[1] - 60, "Place Write This:", $textColor);
                imagejpeg($img);
                imagedestroy($img);
        }
        /**
         * 参数不能为空值
         * @author L
         * @access public
         * @param string $value 不能为空的参数
         * @return 
         **/
        public static function NotNull(string $value)
        {
                if (!is_string($value))
                        $data = Tool::msg(500, $value . "这不是一个有效的字符串!");
                if ($value === null or $value === "") {
                        $data = Tool::msg(404, "参数不能为空!");
                        return $data;
                } else {
                        return $value;
                }
        }

        /**
         * 获取电脑配置信息
         * @author L
         * @access public
         * @param bool $details 是否详情-选填
         * @return string
         */
        public static function GetComputerInformation(bool $details = false): string
        {
                $Systemtype = php_uname(); //获取电脑操作系统
                $Systemtype = str_replace('Windows NT ALONE 10.0 build 22621 ', '', $Systemtype);
                $Systemtype = str_replace('(', '', $Systemtype);
                $Systemtype = str_replace(')', '', $Systemtype);
                $Systemtype = str_replace('AMD', '系统位数:', $Systemtype);
                $username = get_current_user(); //获取系统用户名
                $ip = Tool::GetIP(); //获取IP地址
                $php = PHP_VERSION; //获取PHP版本

                if ($details) {
                        $value = "操作系统:" . $Systemtype . '<br>' . "用户名:" .
                                $username . '<br>' . "IP地址:" . $ip . '<br>' . "PHP版本:" . $php;
                        return $value;
                } else {
                        $value = $Systemtype . '<br>' . $username . '<br>' .
                                $ip . '<br>' . $php;
                        return $value;
                }
        }
        /**
         * 出栈->(通过递归的使用)[先进后出，后进先出]
         * @author L
         * @access public
         * @param $data 数组(栈)->不管是顺序还是倒序后会进行一次重新倒序
         */
        public static function pop(array $data)
        {
                return array_reverse($data);
        }
        /**
         * 数组参数不能为空值
         * @author L
         * @access public
         * @param array $value 输入的传来的参数
         **/
        public static function ArrNotNull(array $value)
        {
                if (!is_array($value))
                        $data = Tool::msg(500, $value . "这不是一个有效的数组!");
                if ($value === null or $value === "") {
                        $data = Tool::msg(500, "数组参数不能为空!");
                        return $data;
                } else {
                        return $value;
                }
        }
        /**
         * 判断查询结果是否为空->(为空则报错)
         * @author L
         * @access public
         * @param $data 传入查询后的内容data
         * @param string $wrong 传入报错提示内容wrong
         * @param string $success 传入正确提示内容success
         */
        public static function JudgmentNull($data, string $wrong = "不能为空", string $success = "OK")
        {
                $data = self::NotNull($data);
                if ($data == "[]") {
                        $data = Tool::msg(500, $wrong);
                        return $data;
                } else {
                        $data = Tool::msg(200, $success);
                        return $data;
                }
        }
        /**
         * 查询是否返回值->(查询成功和查询失败)
         * @author L
         * @access public
         * @param  $data 传入判断的内容data
         * @param  string $wrong 传入失败提示内容wrong
         * @param  string $success 传入成功提示内容success
         */
        public static function JudgmentSuccess($data, $wrong = "失败!", $success = "成功!")
        {
                $data = self::NotNull($data);
                if ($data == 1) {
                        $value = Tool::msg(200, $success);
                        return $value;
                } else {
                        $value = Tool::msg(500, $wrong);
                        return $value;
                }
        }
        /**
         * 中英文字符->(中文字符转换英文字符)
         * @author L
         * @access public
         * @param string $character 中文字符
         */
        public static function ChineseAndEnglish(string $character)
        {
                $character = self::NotNull($character);
                $data = [
                        '，' => ',',
                        '。' => '.',
                        '？' => '?',
                        '；' => ';',
                        '‘' => "'",
                        '“' => '"',
                        '：' => ':',
                        '【' => '[',
                        '】' => ']',

                ];
                return $data[$character];
        }
        /**
         * 获取Token值(转换)->(根据当前日期的转换Token)
         * @author L
         * @access public
         * @param string $time 当前时间
         */
        public static function TokenChange(string $time)
        {
                if (!is_string($time))
                        $value = Tool::msg(500, $time . "这不是一个有效的时间!");
                $time = self::NotNull($time);
                $value = md5($time); //转md5
                $value = str_rot13($value); //转ROT13
                //分散拼接
                $first = substr($value, 0, 5);
                $second = substr($value, 10, 15);
                $value = $first . $second;
                return $value;
        }

        /**
         * JSON返回类型
         * @static
         * @param array $data 转换为JSON类型的数据
         * @return mixed Json数据类型
         */
        public static function JSON(array $data)
        {
                header("Content-Type: application/json"); //修改类型为JSON类型
                return json_encode($data, JSON_PRETTY_PRINT);
        }


        /**
         * 自动生成账号和密码（乱）
         * @author L 
         * @static
         * @access public
         * @param bool $write 是否写入表-选填
         * @param mixed $model 模型层-选填->(这里推荐用登录)
         * @param array $field 写入的字段-选填->(这里默认["login_user","login_password"])->只能支持两条
         * TODO 用来快速新建账号和密码。
         * @date 2023/10/09
         */
        public static function MakeUserAndPassword(bool $write = false, mixed $model = "", array $field = ["login_user", "login_password"])
        {
                $con = [];
                $con["User"] = substr(self::Salt(self::GetNewDate("New")), 1, 8);
                $con["Password"] = substr(self::Salt(self::GetNewDate("Day")), 1, 8);
                //!排除异常
                if (count($field) != 2)
                        return self::msg(404, "参数错误！");
                if (!$write)
                        return $con; //*返回数据
                if ($write) {
                        $data = [
                                $field[0] => $con["User"],
                                $field[1] => $con["Password"],
                        ];
                        $end = $model::create($data);
                        return Tool::msg(200, "新建成功！");
                }
        }
        /**
         * 加密盐
         * @static
         * @author L
         * @access public
         * @param string $key 盐值-必填
         * @param bool $status 是否规格化-选填
         * @param string $$malization 规格化字符-选填
         * @date 2023/10/05
         * TODO 用于使用高级性且安全性强的功能使用的加密盐。
         * @return string
         */
        public static function Salt(string $key, bool $status = false, string $malization = "-"): string
        {
                //将盐md5编码
                $key_md5 = md5($key);
                //将盐sha1哈希编码
                $key_sha1 = sha1($key);
                //将盐base64_encode->base编码
                $key_base64_encode = base64_encode($key);
                //将盐urlencode url路由编码
                $key_urlencode = urlencode($key);
                if (!$status) {
                        $key = substr($key_md5, 0, 100) . substr($key_base64_encode, 0, 100) . substr($key_urlencode, 0, 100) . substr($key_sha1, 0, 100);
                        $key = str_rot13($key); //转换rot13
                }
                if ($status) {
                        $key = substr($key_md5, 0, 100) . $malization . substr($key_base64_encode, 0, 100) . $malization . substr($key_urlencode, 0, 100) . $malization . substr($key_sha1, 0, 100);
                        $key = str_rot13($key); //转换rot13
                }
                return $key;
        }
        /**
         * 时间线Token验证器->(获取当前日期的Token值)
         * @author L
         * @access public
         * @return string
         */
        public static function Token(): string
        {
                $time = Tool::GetNewDate('Year');
                $value = self::TokenChange($time);
                return $value;
        }
        /**
         * 中英文字符替换->(中英文字符转换)
         * @author L
         * @access public
         * @param string $character 中文字符character-必填
         */
        public static function ChineseChangeEnglist(string $character)
        {
                $value = self::ChineseAndEnglish($character);
                return $value;
        }
        /**
         * 下划线转陀峰->(不允许被大写)
         * @author L
         * @access public
         * @param string $data 内容字符串data-必填
         * @param bool $change 是否进行转换change-选填
         */
        public static function ChangeToFeng(string $data, bool $change = true)
        {
                $data = Tool::NotNull($data);
                //判断change是否为true
                if ($change) {
                        //去除下划线
                        $data = str_replace("_", "", $data);
                        // //首字母大写
                        $data = ucfirst($data);
                        return $data;
                } else {
                        $data = str_replace("_", "", $data);
                }
        }
        /**
         * 自拟报错
         * @static
         * @param string $data 报错内容
         * @return mixed
         */
        public static function abort(string $data): mixed
        {
                return Tool::msg(500, $data);
        }

        /**
         * 参数批量验证器
         * @author L
         * @access public
         * @param array $params  参数-必填
         * @param array $verification 验证条件-必填
         * @date 2023/9/13 
         * TODO 用于前端或前台POST传递参数时候的验证
         * @return mixed
         */
        public function BatchVerification(array $params, array $verification): mixed
        {
                //*获取param参数所有Key键
                $params_keys = array_keys($params);
                for ($i = 0; $i < count($verification); $i++) {
                        //*防双斜杆
                        $verification[$i] = str_replace("\/", "", $verification[$i]);
                        //*转换数组获取参数索引
                        $verification_arr = explode("/", $verification[$i]);
                        $Verification_key = in_array($verification_arr[0], $params_keys); //*判断是否在数组里面
                        if (!$Verification_key && $verification_arr[2] == "require") {
                                $params[$verification_arr[0]] = "";
                        } //*没有的话就自动填充为null(require)
                        if (!$Verification_key && $verification_arr[2] == "unrequire") {
                                $params[$verification_arr[0]] = "";
                        } //*没有的话就自动填充为空字符串(unrequire)
                        //!错误类型排除,Verification_key(require->必填,unrequire->不必填)close
                        if ($verification_arr[2] == "require" || $verification_arr[2] == "unrequire") {
                                //?是否设置必填(require)
                                //!为空则扔出异常
                                if ($verification_arr[2] == "require" && $params[$verification_arr[0]] == "") {
                                        return self::msg(500, $verification_arr[1] . "不能为空！");
                                }
                                if ($params[$verification_arr[0]] === null) {
                                        return self::msg(500, $verification_arr[1] . "不能为空！");
                                }
                        } else {
                                return Tool::msg(500, "参数批量验证器出现未知类型！", [], int: "错误异常！");
                        }
                }
                return $params;
        }

        /**
         * 陀峰转下划线->(不允许被小写)
         * @author L
         * @param string $data 内容字符串data-必填
         * @param string $changedata 改变字段分离的内容changedata-必填
         * @param bool $change 是否进行转换change-选填
         */
        public static function ChangeUnderline(string $data, string $changedata, bool $change = true)
        {
                $data = Tool::NotNull($data);
                //判断change是否为true
                if ($change) {
                        $data_len = strlen($data); //get all length
                        $changedata_len = strpos($data, $changedata); //获取截取字符首长度get first letter spilt length
                        //截加"_"
                        $symbol = "_";
                        $value = strtolower(substr($data, 0, $changedata_len)) . $symbol . strtolower(substr($data, $changedata_len, $data_len)); //首字母小写lower of first letter
                        return $value;
                } else {
                        $value = strtolower($data);
                        return $value;
                }
        }
        /**
         * 删除目录->(删除指定目录文件夹)
         * @author L
         * @static
         * @date 2023/10/10
         * @param string $url
         * @return mixed
         */
        public static function DeleteDir(string $url)
        {
                $data = rmdir($url);
                return self::msg(200, "删除成功！");
        }
        /**
         * 删除文件->(删除指定文件)
         * @author  L
         * @access public
         * @param string $url 删除文件的路径
         * @todo 主要删除一些不需要的文件或其余内容
         */
        public static function DeleteFile(string $url)
        {
                //?判断文件是否存在
                if (file_exists($url)) {
                        $delete = unlink($url); //!删除文件
                        if ($delete) {
                                return Tool::msg(200, "删除成功!", [], "文件删除成功!");
                        } else {
                                return Tool::msg(404, "删除成功!", [], "文件删除失败!");
                        }
                } else {
                        return Tool::msg(404, "文件不存在!");
                }
        }
        /**
         * 自动生成文件夹->(生成指定文件夹)
         * @author L
         * @access public
         * @param string $url 生成路径url-必填
         * @param string $name 生成文件夹名字name-必填
         */
        public static function AutomaticOs(string $url, string $name)
        {
                $url = Tool::NotNull($url);
                $name = Tool::NotNull($name);
                $value = "/" . $name; //二次转换(url)twice change
                $data = $url . $value;
                //判断文件夹是否存在judgment os whether have
                if (!file_exists($data)) {
                        mkdir($data); //生成文件夹
                        $value = Tool::msg(200, "生成成功!");
                        return $value;
                } else {
                        $value = Tool::msg(404, "生成失败!");
                        return;
                }
        }
        /**
         * 写入文件["w"]
         */
        public static function WriteFile(string $url, string $data)
        {
                $value = fopen($url, "w"); //截加
                $value = fwrite($value, $data); //写入
                return $value;
        }
        /**
         * 自动生成文件->(指定url路径生成某种文件)
         * @author L
         * @access public
         * @param string $url 路径url-必填
         * @param string $name 文件名字name-必填
         * @param string $suffix 文件后缀suffix-选填
         * @param string $data 写入文件的内容(空或最终结果)data-选填
         * @param string $type 写入类型 选填 默认为 'w'
         */
        public static function AutomaticFile(string $url, string $name, string $suffix = "", string $data = "", string $type = "w")
        {
                $url = Tool::NotNull($url);
                $name = Tool::NotNull($name);
                if ($suffix == "") {
                        $value = fopen($url . "/" . $name, $type); //截加
                        $value = fwrite($value, $data); //写入
                        return $value;
                } else {
                        $suffix_value = '.' . $suffix; //二次转换(后缀)twice change
                        $name_value = $name . $suffix_value; //拼接
                        if ($type == "a") {
                                $value = fopen($url . "/" . $name_value, "a"); //截加

                        } else {
                                $value = fopen($url . "/" . $name_value, "a"); //截加

                        }
                        $value = fwrite($value, $data); //写入
                        return $value;
                }
        }
        /**
         * 文件写入->(指定文件写入内容)
         * @author L
         * @access public
         * @param string $url 文件路径url-必填
         * @param string $data 文件写入内容data-选填
         */
        public static function FileWrite(string $url, string $data = "")
        {
                $url = Tool::NotNull($url);
                $file = fopen($url, 'a');
                fwrite($file, $data);
                $value = Tool::msg(200, "写入成功!");
                return $value;
        }
        /**
         * 文件读取
         */
        public static function FileRead(string $url)
        {
                $file = fopen($url, 'r');
                return fread($file, 2560000);
        }
        /**
         * 获取本机IP地址->(获取本机IP地址)
         * @author L 
         * @access public
         */
        public static function GetIP()
        {
                //获取IP地址get IP localhost
                $value = gethostbyname(gethostname());
                return $value;
        }
        /**
         * 获取当前时间->(获取自定类型的时间戳)
         * @author L
         * @access public
         * @param string $type 时间类型(New->获取当前全部时间戳.Year->获取年、月、日的时间戳.Day->获取时、分、秒的时间戳)-选填
         */
        public static function GetNewDate(string $type)
        {
                $type = Tool::NotNull($type);
                switch ($type) {
                        case 'New':
                                $value = date('Y.m.d-H.i:s');
                                return $value;
                        case 'Year':
                                $value = date('Y.m.d');
                                return $value;
                        case 'Day':
                                $value = date('H.i:s');
                                return $value;
                        default:
                                return;
                }
        }
        /**
         * 生成唯一id->(guid)
         * @author L
         * @access public
         * @param string $name 传入内容(字段信息)(空或最终结果)name-选填
         * @param bool $isStrong 是否强类型写入(强制写入时间戳Token)isStrong-选填
         */
        public static function AutomaticID(string $name = '', bool $isStrong = false)
        {
                //?获取当前时间戳
                $name = json_encode($name); //解析json
                //避免出现大写(转小写)(数据库对大小写铭感)
                //编译md5散列
                //?是否强类型写入，里面混杂着时间戳
                if ($isStrong) {
                        $newDateTime = self::GetNewDate('New');
                        $value = md5($newDateTime . $name);
                } else if (!$isStrong) {
                        $value = md5($name);
                }
                $value = strtolower($value);
                //截加."."
                $symbol = '.';
                $value = substr($value, 0, 5) . $symbol
                        . substr($value, 4, 9) . $symbol
                        . substr($value, 8, 13) . $symbol
                        . substr($value, 12, 12);
                return $value;
        }
        /**
         * Message警示框->(后端给前端和前台接口的返回提示)->多种
         * @author L
         * @access public
         * @param int $code 返回代参code-必填
         * @param string $msg 返回内容msg-必填
         * @param array  $data 成功返回参数(空或最终结果)data-选填
         * @param string $func 返回的方法名称func-选填
         * @return mixed 返回JSON数据
         */
        public static function Message(int $code, string $msg, array|object $data = [], ?string $func = ""): mixed
        {
                return CodeConfig::Code(code: $code, msg: $msg, data: $data, func: $func);
        }
        /**
         * Message警示框->(后端给前端和前台接口的返回提示)->轻便
         * @author L
         * @access public
         * @param int $code 返回代参code-必填
         * @param string $msg 返回内容msg-选填
         * @param array $data 成功返回参数(空或最终结果)data-选填
         * @param string $int 注解int - 选填
         * @return mixed 返回JSON数据
         */
        public static function msg(int $code, string $msg, ?array $data = [], ?string $int = "")
        {
                $msg = Tool::NotNull($msg);
                $retrurn_data = ['code' => $code];

                switch ($code) {
                        case 200:
                                /**成功回调 */
                                $retrurn_data['msg'] = $msg;
                                $retrurn_data['data'] = $data;
                                $retrurn_data['int'] = $int;
                                break;
                        case 404:
                                /**失败回调 */
                                $retrurn_data['msg'] = $msg;
                                $retrurn_data['int'] = $int;
                                break;
                        case 500:
                                /**异常回调 */
                                Error::create($msg, __FILE__, __LINE__);
                                break;
                        default:
                                /**异常回调 */
                                Error::create("Code参数出问题！");
                                break;
                }
                return self::JSON($retrurn_data);
        }

        /**
         * 绑定表字段->(可绑定多种字段)
         * @author L
         * @access public
         * @param string $infomat 前缀字段名字-必填
         * @param string  $behind 后缀字段名字-必填
         */
        public static function Bind(string $infomat, string $behind)
        {
                $infomat = Tool::NotNull($infomat);
                $behind = Tool::NotNull($behind);
                //全部都定义小写
                $infomat = strtolower($infomat);
                $behind = strtolower($behind);
                //截加"-"
                $value = $infomat . "_" . $behind;
                return $value;
        }
        /**
         * MySQL(date)类型->(对应封号和解封的Function)[如果需要可以使用]
         * @author L
         * @access public
         * @return string
         */
        public static function MySQLDate(): string
        {
                $timestamp = time(); //获取当前时间戳
                $value = date("y-m-d", $timestamp); //转换成24小时制
                $value = "20" . $value;
                return $value;
        }
        /**
         * MySQL(date)类型->(对应封号和解封的Function)[如果需要可以使用]
         * @author L
         * @access public
         * @return string
         */
        public static function MySQLDataTime(): string
        {
                $timestamp = time(); //获取当前时间戳
                $value = date("Y-m-d H:i:s", $timestamp); //转换成24小时制
                return $value;
        }
        /**
         * MySQL(time)类型->(对应封号和解封的Function)[如果需要可以使用]
         * @author L
         * @access public
         * @return string
         */
        public static function MySQLTime(): string
        {
                $timestamp = time(); //获取当前时间戳
                $value = date("H:i:s", $timestamp); //转换成24小时制
                return $value;
        }
}
