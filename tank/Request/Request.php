<?php
namespace tank\Request;

/**
 * Request请求类
 * @author LL
 */

use tank\Tool\Tool;

class Request
{
        /**
         * 获取模型层下指定需求写入字段参数
         * @access public
         * @static
         * @param string $model 模型层名称 必填
         * @param string $type 模型层类型 选填 默认为 get
         * @return array
         */
        public static function getModelParam(string $model, string $type = 'get'): array
        {
                $data = [];
                $getModel = "\app\model\\$model";
                $field = array_keys($getModel::$writefield);
                if ($type == 'get') {
                        $params = self::param();
                        foreach ($params as $k => $v) {
                                if (in_array($k, $field))
                                        $data[$k] = $v;
                        }
                }
                if ($type == 'post') {
                        $params = self::postparam();
                        foreach ($params as $k => $v) {
                                if (in_array($k, $field))
                                        $data[$k] = $v;
                        }
                }
                return $data;
        }
        /**
         * 获取POST参数
         * @access public
         * @static
         * @date 2023/11/20
         * TODO用来直接获取参数所有数据数组。
         * @return mixed
         */
        public static function postparam(): mixed
        {
                //?区分Content-Type
                $Type = self::headers("Content-Type");
                if ($Type == "application/json") {
                        $post = file_get_contents("php://input");
                        $post = json_decode($post); //JSON解码
                        if ((array) $post == [])
                                return [];
                        return (array) $post;
                } else {
                        // if (!$_POST or $_POST == [])
                        // return Tool::Message(404, "没有携带任何参数！");
                        return $_POST ?? [];
                }
        }
        /**
         * 参数转换
         * @date 2023/12/01
         * TODO用来GET请求中转换请求字符串
         * @access public
         * @static
         * @param array $data 转换数据参数data 必填
         * @return string
         */
        public static function ChangeParams(array $data): string
        {
                $keysArr = [];
                //*获取key键
                foreach (array_keys($data) as $keys) {
                        array_push($keysArr, $keys);
                }
                $valuesArr = [];
                //*获取value值
                foreach (array_values($data) as $values) {
                        array_push($valuesArr, $values);
                }
                //*字符串拼接
                $end = "";
                for ($i = 0; $i < count($keysArr); $i++) {
                        $end .= "{$keysArr[$i]}={$valuesArr[$i]}&";
                }
                //*拼接
                $end = "?" . $end;
                //*去掉最后一个&
                $end = substr($end, 0, strlen($end) - 1);
                return $end;

        }
        /**
         * 获取GET参数
         * @access public
         * @date 2023/12/01
         * @static
         * TODO用来直接获取参数所有数据数组。
         * @return mixed
         */
        public static function param(): mixed
        {
                return $_GET ?? [];
        }
        /**
         * 获取参数值[只拿值，不拿键]
         * @access public
         * @date 2024/4/8
         * @static
         * @param string $type 参数类型 选填 默认为 post [post or get]
         * @return array
         */
        public static function getValue(string $type = 'post'): array
        {
                if ($type == 'post')
                        return array_values(self::postparam());
                if ($type == 'get')
                        return array_values(self::param());
                return [];
        }
        /**
         * 获取请求头
         * @static
         * @date 2023/12/01
         * TODO用来获取请求头信息数据
         * @param string $headersname 请求头名字 必填
         * @return mixed
         */
        public static function headers(string $headersname): mixed
        {
                $headersname = str_replace("_", "-", $headersname); //!防止出现下划线
                $headers = apache_request_headers();
                return $headers[$headersname] ?? null;

        }
        /**
         * 获取响应头
         * @access public
         * @static
         * @date 2023/12/01
         * @param string $responsename 相应头名字 选填 默认为 ‘’
         * @return mixed
         */
        public static function response(string $responsename = ''): mixed
        {
                // return empty($responsename) ? apache_response_headers() : apache_response_headers()[$responsename] ?? null;
                $response = headers_list();
                $data = [];
                foreach ($response as $k => $v) {
                        $slice = explode(":", $v);
                        $data[$slice[0]] = $slice[1];
                }
                return $data;
        }


}