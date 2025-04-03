<?php
namespace app\void;

use tank\Attribute\Attribute;
use \tank\BaseController;
use \app\model\Dictionary as ModelDictionary;
use tank\Command\Command;
use tank\Request\Request;
use app\common\interface\Dictionary as InterfaceDictionary;

use function tank\{BathVerParams,Success,VerificationInclude};

class Dictionary extends BaseController implements InterfaceDictionary
{
        /**
         * 新建字典
         * !需要区分的是父级和子级的字典
         */
        public function CreateDictionary()
        {
                (new Attribute("FUNCTION", "建立一个字典->父级或子级"));
                BathVerParams("POST", VerificationInclude('Dictionary')['addDictionary']);
                $params = Request::postparam();
                if (empty($params['dictionaryFatherGuid'])) {
                        $data =
                                [
                                        $params['dictionaryName'],
                                        $params['dictionaryType'],
                                ];
                        for ($i = 0; $i < count(ModelDictionary::$writefield) - 2; $i++) {
                                $data[] = "";
                        }
                } else {
                        if (!(new ModelDictionary)->where(['dictionary_guid' => $params['dictionaryFatherGuid']])->Once()) {
                                echo \tank\Error("字典建立失败!(没有该父级Guid)", function: __FUNCTION__);
                                die;
                        }
                        $data = [
                                $params['dictionaryName'],
                                $params['dictionaryType'],
                                $params['dictionaryKey'],
                                $params['dictionaryValue'],
                                $params['dictionaryFatherGuid'],
                        ];
                }
                (new ModelDictionary)->Modelcreate($data);
                echo Success("字典建立成功!", function: __FUNCTION__);
        }
        /**
         * 获取字典菜单树
         */
        public function getDictionaryTree()
        {
                $select = (new ModelDictionary)->find(['dictionary_father_guid' => ""]);
                for ($i = 0; $i < count($select); $i++) {
                        $select[$i]->children = (new Command("dictionary|dictionary_father_guid={$select[$i]->dictionary_guid}|find"))->data;
                }
                echo Success("获取成功!", $select);
        }
}