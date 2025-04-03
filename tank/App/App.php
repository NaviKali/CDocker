<?php
/**
 * App类 [App场景化处理]
 */
namespace tank\App;

use tank\Func\Func;
use tank\Request\Request;
use tank\Web\http;
use function tank\{getRoot, getAutograph};
use tank\Error\httpError;

class App
{
    /**
     * App代参
     */
    public int $AppCode;
    /**
     * 是否为入口文件
     */
    protected bool $isPublicFile;
    /**
     * App类Base64解码参数
     */
    protected array $AppBaseParams = [];
    /**
     * 不可调用类列表
     */
    protected array $NotCallClassList = [];
    /**
     * App验证
     */
    public function __construct(mixed $class)
    {
        $AppConfig = require(getRoot() . "config/App.php");

        $this->AppCode = $AppConfig["AppCode"];

        //!代参禁止访问接口
        if ($this->AppCode == 404)
            throw new httpError("接口禁止访问！");

        $this->NotCallClassList = $AppConfig["AppNotCallClass"];

        if (is_subclass_of($class, "tank\BaseController")):
            header("Content-Type:application/json");
            $class = md5($class);
            header("Autograph:$class");//*签名
        endif;

        $this->VerIsPublicFile();

        if ($this->isPublicFile) {
            //?是否开启App场景化
            if (!$AppConfig["IsStartApp"]) {
                if ($AppConfig["AppParamsType"] == "GET")
                    $this->AppBaseParams = Request::param();
                if ($AppConfig["AppParamsType"] == "POST")
                    $this->AppBaseParams = Request::postparam();
                return;
            }


            //?是否为不可调用类
            $Autograph = getAutograph();
            for ($v = 0; $v < count($AppConfig["AppNotCallClass"]); $v++) {
                if (
                    $Autograph == md5($AppConfig["AppNotCallClass"][$v])
                ) {
                    \tank\Error\error::create("当前类不可调用!", __FILE__, __LINE__);
                }
            }
            //?是否为入口文件
            if ($AppConfig["AppParamsType"] == "GET") {
                $this->AppBaseParams = Func::BaseDeCodeUrl();
            } else if ($AppConfig["AppParamsType"] == "POST") {
                $post = Request::postparam();
                $keys = array_keys($post);
                $values = [];
                $regex = "/^[A-Za-z0-9+\/=-_]*$/";
                for ($v = 0; $v < count(array_values($post)); $v++) {
                    if (preg_match($regex, array_values($post)[$v]))
                        array_push($values, base64_decode(array_values($post)[$v]));
                    else
                        array_push($values, array_values($post)[$v]);
                }
                $post = array_combine($keys, $values);
                $this->AppBaseParams = $post;
            }

            //?是否开启严格验证
            if ($AppConfig["IsStartStrict"]) {
                $type = [];
                if ($AppConfig["AppParamsType"] == "GET")
                    $type = Request::param();
                if ($AppConfig["AppParamsType"] == "POST")
                    $type = Request::postparam();
                foreach ($type as $k => $v) {
                    $regex = "/^[A-Za-z0-9+\/=-_]*$/";
                    if (!preg_match($regex, base64_decode($v)))
                        \tank\Error\error::create("Base解码错误!");
                }
            }
        }
    }
    /**
     * 获取不可调用类列表
     * @return array
     */
    public function getNotCallClassList(): array
    {
        return $this->NotCallClassList;
    }
    /**
     * 验证是否为入口文件
     */
    protected function VerIsPublicFile()
    {
        str_contains(Func::getUrl(), "public") ? $this->isPublicFile = true : $this->isPublicFile = false;
    }
    /**
     * 获取解码后的参数
     * @access public
     * @param bool $isGetValue 是否只拿Value 选填 默认为 false
     * @return array
     */
    public function getAppParams(bool $isGetValue = false): array
    {
        if ($isGetValue) {
            $data = [];
            foreach ($this->AppBaseParams as $k => $v) {
                $data[] = $v;
            }
            return $data;
        }

        return $this->AppBaseParams;
    }
}

