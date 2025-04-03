<?php

namespace app\common\logic;

use app\common\logic\systemctl as Logicsystemctl;
use app\common\logic\command as Logiccommand;

class docker
{
    const ACTION_GETCONTAINERLIST = "getContainerList";
    const ACTION_GETCONTAINERALLLIST = "getContainerAllList";
    const ACTION_GETIMAGESLIST = "getImagesList";
    const ACTION_CLOSECONTAINER = "closeContainer";
    const ACTION_RESTARTCONTAINER = "restartContainer";
    /**
     * 系统类型
     * 
     * @access public
     * @var array
     */
    public array $systemctlType = [
        Logicsystemctl::SYSTEMCTL_WINDOWS,
        Logicsystemctl::SYSTEMCTL_LINUX,
    ];
    /**
     * 操作标签
     * 
     * @access public
     * @var array
     */
    public array $tags = [
        "get" => [
            self::ACTION_GETCONTAINERLIST,
            self::ACTION_GETCONTAINERALLLIST,
            self::ACTION_GETIMAGESLIST,
        ],
        "action" => [
            self::ACTION_CLOSECONTAINER,
            self::ACTION_RESTARTCONTAINER,
        ]
    ];
    /**
     * 切块大小
     * 
     * @access public
     * @var array
     */
    public array $chunkSize = [
        self::ACTION_GETIMAGESLIST => 5,
        self::ACTION_GETCONTAINERLIST => 8,
        self::ACTION_GETCONTAINERALLLIST => 8,
    ];
    /**
     * 命令执行
     * 
     * @access public
     * @var array
     */
    public array $action = [
        self::ACTION_GETCONTAINERLIST => "docker ps",
        self::ACTION_GETCONTAINERALLLIST => "docker ps -a",
        self::ACTION_GETIMAGESLIST => "docker images",
        self::ACTION_CLOSECONTAINER => "docker stop\t",
        self::ACTION_RESTARTCONTAINER => "docker restart\t"
    ];
    /**
     * 获取数据
     * 
     * @access public
     * @var array
     */
    public array $data = [];
    /**
     * 构造一个docker获取器
     * 
     * @access public
     * @param array|string $tags 操作标签 选填
     * @param string $type 系统类型 选填
     */
    public function __construct(array|string $tags = [], string $type = Logicsystemctl::SYSTEMCTL_LINUX)
    {
        if (!in_array($tags, $this->tags["action"])) {
            $this->data = is_string($tags) ? [$tags => $this->Chunk($this->DockerCommand($tags), $this->chunkSize[$tags])] : (function () use ($tags): array{
                $data = [];
                foreach ($tags as $k => $v) {
                    $data[$v] = $this->Chunk($this->DockerCommand($v), $this->chunkSize[$v]);
                }
                return $data;
            })();
        }
    }
    /**
     * Docker命令
     * 
     * @access public
     * @param string $tag 命令 必填
     * @return bool|string|null|array
     */
    public function DockerCommand(string $tag): bool|string|null|array
    {
        $command = Logiccommand::Start($this->action[$tag]);

        #处理特殊字符
        $command = preg_replace('/, +/', "\t", $command);
        $command = preg_replace('/About an+/', "About\tAn", $command);
        $command = preg_replace('/About a+/', "About\tA", $command);
        $command = preg_replace('/Up Less +/', "Up\tLess", $command);
        $command = preg_replace('/Up About +/', "Up\tAbout", $command);
        $command = preg_replace('/nginx -g +/',"nginx\t-g\t",$command);
        $command = preg_replace('/daemon of+/',"daemon\tof",$command);



        $command = explode("\n", $command);

        unset($command[sizeof($command) - 1]);

        $command = match ($tag) {
            self::ACTION_GETIMAGESLIST => (function () use (&$command): array{
                    for ($i = 0; $i < sizeof($command); $i++) {
                        $command[$i] = str_replace(" ", "&&", $command[$i]);
                        $command[$i] = explode("|", preg_replace("/&&+/", "|", $command[$i]));
                    }
                    $command[0][2] = $command[0][2] . PHP_EOL . $command[0][3];
                    unset($command[0][3]);
                    usort($command[0], function (): void{});
                    for ($i = 1; $i < sizeof($command); $i++) {
                        $command[$i][3] = $command[$i][3] . PHP_EOL . $command[$i][4] . PHP_EOL . $command[$i][5];
                        unset($command[$i][4], $command[$i][5]);
                        usort($command[$i], function (): void{});
                    }
                    return $command;
                })(),
            self::ACTION_GETCONTAINERLIST, self::ACTION_GETCONTAINERALLLIST => (function () use (&$command): array{
                    for ($i = 0; $i < sizeof($command); $i++) {
                        $command[$i] = str_replace(" ", "&&", $command[$i]);
                        $command[$i] = explode("|", preg_replace("/&&+/", "|", $command[$i]));
                    }
                    $command[0][0] = $command[0][0] . PHP_EOL . $command[0][1];
                    unset($command[0][1]);
                    usort($command[0], function (): void{});
                    for ($i = 1; $i < sizeof($command); $i++) {
                        // $command[$i][2] = $command[$i][2] . PHP_EOL . $command[$i][3] . PHP_EOL . $command[$i][4] . PHP_EOL . $command[$i][5];
                        $command[$i][3] = $command[$i][3] . PHP_EOL . $command[$i][4] . PHP_EOL . $command[$i][5];
                        $command[$i][6] = $command[$i][6] . PHP_EOL . $command[$i][7] . PHP_EOL . $command[$i][8];
                        unset($command[$i][4], $command[$i][5], $command[$i][7],$command[$i][8]);
                        usort($command[$i], function (): void{});
                    }

                    return $command;
                })(),
        };
        return $command;
    }
    /**
     * 数组切块
     * 
     * @access public
     * @param array $item 数组 必填
     * @param int $size 大小 必填
     * @return array
     */
    public function Chunk(array $item, int $size): array
    {
        return array_chunk($item, $size)[0];
    }
    /**
     * 停止容器
     * 
     * @access public
     * @param string|array $containers 容器集合
     * @return string|null
     */
    public function closeContainer(string|array $containers): string|null
    {
        $containers = is_string($containers) ? str_replace(",", "\t", $containers) : $containers;
        $containers = is_array($containers) ? join("\t", $containers) : $containers;
        return Logiccommand::start($this->action[self::ACTION_CLOSECONTAINER] . $containers);
    }
    /**
     * 重启容器
     * 
     * @access public
     * @param string|array $containers 容器集合
     * @return string|null
     */
    public function restartContainer(string|array $containers): string|null
    {
        $containers = is_string($containers) ? str_replace(",", "\t", $containers) : $containers;
        $containers = is_array($containers) ? join("\t", $containers) : $containers;
        return Logiccommand::start($this->action[self::ACTION_RESTARTCONTAINER] . $containers);
    }

    /**
     * 获取所有容器中的类型列表和总数
     * 
     * @access public
     * @return array
     */
    public function getContainerListTypeAndCount(): array
    {
        $containerList = $this->data[self::ACTION_GETCONTAINERALLLIST];
        $data = [];
        $count = [];
        for ($i = 1; $i < sizeof($containerList); $i++) {
            if (!in_array($containerList[$i][1], $data))
                $data[] = $containerList[$i][1];
        }
        foreach ($data as $k => $v) {
            $number = 0;
            for ($i = 1; $i < sizeof($containerList); $i++) {
                if($containerList[$i][1] == $v){
                    $number++;
                }
            }
            $count[] = $number;
        }



        return ["type"=>$data,"count"=>$count];
    }

}