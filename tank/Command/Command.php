<?php
/**
 * Command命令式--数据库查询
 * *格式为:表-赛选条件-查询
 * !Command可能会存在一些错误或不理想的需要。
 * TODO用来以独特的命令写法来操作MongoDB数据库
 */
namespace tank\Command;

use tank\MG\MG;

class Command extends \tank\MG\MG
{
        /**
         * 接收命令
         * @param string $Message 命令行 必填
         * @param array $data 查询后的数据 选填 默认为[]
         */
        public function __construct(public string $Message, public array $data = [])
        {
                $this->IsOpenSQL();
                $this->Run($this->Message);
        }
        /**
         * 验证数据库是否开启
         */
        protected function IsOpenSQL()
        {
                MG::IsClient();
        }
        /**
         * 执行命令行
         * @access protected
         * @param string $Message 命令行 必填
         */
        protected function Run(string $Message)
        {
                $Msg = explode('|', $Message);
                $TableCommand = $Msg[0];
                $WhereCommand = $Msg[1];
                $SelectCommand = $Msg[2];
                if (sizeof($Msg) != 3)
                        return \tank\Error\error::create("此命令无效!");
                $Where = $this->WhereChange($WhereCommand);
                $End = $SelectCommand == 'find' ? (new MG($TableCommand))->$SelectCommand($Where) : (new MG($TableCommand))->where($Where)->$SelectCommand();
                $this->data = $End;
        }
        /**
         * Where转换
         * @access protected
         * @param string $Where Where命令
         * *Where命令格式:赛选条件&赛选条件
         */
        protected function WhereChange(string $Where)
        {
                $con = [];
                if ($Where == 'None' or $Where == 'none')
                        return $con;
                $mainWhere = explode("&", $Where);
                for ($i = 0; $i < count($mainWhere); $i++) {
                        //*整数转换
                        $value = preg_match("/[0-9]/", explode("=", $mainWhere[$i])[1]) && !preg_match("/[a-z]/", explode("=", $mainWhere[$i])[1]) ? (int) explode('=', $mainWhere[$i])[1] : explode('=', $mainWhere[$i])[1];
                        $con[explode('=', $mainWhere[$i])[0]] = $value;
                }
                return $con;

        }
}