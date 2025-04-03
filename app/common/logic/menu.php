<?php

namespace app\common\logic;


class menu
{
    /**
     * 默认值
     * 
     * @access public
     * @var array
     */
    public array $default = [
        "首页" => [
            "name" => "home",
            "to" => "./home",
            "children"=>[],
        ],
        "容器"=>[
            "name"=>"container",
            "children"=>[
                "容器列表"=>[
                    "name"=>"containerList",
                    "to"=>"./container/List"
                ],
            ],
        ],
        "镜像"=>[
            "name"=>"image",
            "children"=>[
                "镜像列表"=>[
                    "name"=>"imageList",
                    "to"=>"./"
                ],
            ],
        ],
    ];
}