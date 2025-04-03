<?php
namespace FromExec;


class index
{
        /**
         * 欢迎语
         */
        public function Weclome()
        {
                echo "Weclome to TankExec!(灵感来源:Laravel框架 )\n";
                sleep(1);
                echo "可以通过已给的命令使用，也可以自定义属于自己的命令！\n";
                sleep(1);
                echo "可以通过index:Exec来创建属于自己的命令。\n";
        }
        /**
         * 创建自定义命令
         */
        public function Exec()
        {
                echo "请输入命令名字:";
                $ExecName = readline();
                //*创建命令文件PHP
                echo "等待创建!\n";
                sleep(1);
                if (file_exists(getcwd() . "\\.exec\\FromExec\\$ExecName.php")) {
                        echo "文件已存在!\n";
                        return true;
                }
                $file = fopen(getcwd() . "\\.exec\\FromExec\\$ExecName.php", 'a');
                fwrite($file, "<?php\n namespace FromExec;\nclass $ExecName{\n\n}");
                sleep(1);
                echo "创建完成!\n";
        }
        /**
         * 获取命令列表
         */
        public function getExecList()
        {
                foreach (glob(getcwd() . "\\.exec\\FromExec\\*.php") as $key => $value) {
                        echo str_replace(".php","",str_replace(getcwd() . "\\.exec\\FromExec\\", "", $value)) . "\n";
                }
        }
}
