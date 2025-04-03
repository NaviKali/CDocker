<?php


namespace tank\MG;

use config\Code;
use Error;
use tank\Cookie\Cookie;
use tank\MG\MG as Mongo;
use tank\Request\Request;
use tank\Seesion\Session;
use tank\Tool\Tool;

use function tank\BathVerParams;
use function tank\getCurrentFunctionName;
use function tank\Success;
use function tank\Error;
use function tank\getAPPJSON;

class Operate
{
        /**
         * Token生成器
         * TODO用来生成一条Token值
         * @author LL
         * @access public
         * @static
         * @return string
         */
        public static function MakeToken(): string
        {
                $conten = Tool::AutomaticID('!*$#^%!&#!*/-!@#+_$>.<@$:)\w/-_-+_+->');
                return Tool::Salt(Tool::GetNewDate("New"), true, $conten);
        }
        /**
         * UUID码(用户识别器)
         * TODO这个UUID是用来进行管理员Cookie验证的。
         * @param mixed $value Cookie制 选填 默认为 ''
         * @return void
         */
        public static function UUID(mixed $value = ''): void
        {
                Cookie::SetCookie(getAPPJSON()->COOKIE->UUID->NAME, $value, getAPPJSON()->COOKIE->UUID->TIME);
        }
        /**
         * GUID式编译乱码(不可逆)
         * TODO用来以GUID乱码的形式进行反复编译。
         * @author LL 
         * @access public
         * @static
         * @param string $data 值 必填
         * @param int $number 编译次数 选填
         * @return mixed
         */
        public static function guid_decode(string $data, int $number = 1): mixed
        {
                if ($number == 0) {
                        return $data;
                }
                $data = Tool::AutomaticID($data);
                return self::guid_decode($data, $number - 1);
        }
        /**
         * 中间件(Token)验证器
         * TODO用来验证前端是否传入Token值。
         * @static
         * @access public
         * @author LL
         * @param array $noVer 不进行Token验证的函数名 选填 默认为：[]
         * @return mixed
         */
        public static function VerToken(array $noVer = [])
        {
                /**
                 * 先执行数据库连接
                 */
                \tank\MG\MG::Connect();
                //*进行不需要排除
                for ($a = 0; $a < count($noVer); $a++) {
                        if (getCurrentFunctionName() == $noVer[$a])
                                return;
                }
                $token = Request::headers('Token');
                $find = (new MG('token'))->comment('Token验证.')->where(['token_value' => $token])->select();
                if (!$find) {
                        print_r(Code::Code(502, "登录超时!", [], __FUNCTION__));
                        return die();
                }
                MG::$filter = [];//*初始化过滤内容
        }
        /**
         * 识别数组维度
         * @access public
         * @date 2024/04/10
         * @param array $data 需要识别的数组 必填
         * @param int $index 维度索引 选填 默认为 1
         * !维度索引建议不要修改，默认为1即可。
         * @return int 维数
         */
        public static function RecognitionArrayDimension(array $data, int $index = 1): int
        {
                if (is_array($data[0])) {
                        return self::RecognitionArrayDimension($data[0], $index + 1);
                } else {
                        return $index;
                }
        }


}