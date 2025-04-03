<?php
namespace app\middleware;

class tokenmiddleware
{
        /**
         * Token验证
         */
        public static function Handle()
        {
                \tank\MG\Operate::VerToken([]);
        }
}