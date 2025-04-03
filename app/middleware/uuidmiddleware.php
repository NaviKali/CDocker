<?php

namespace app\middleware;


class uuidmiddleware
{
     /**
      * UUID处理
      */
     public static function Handle(array $params)
     {
          if (isset($_COOKIE["UUID"])) {
               /**
                * UUID存在处理
                */
          } else {
               /**
                * UUID不存在处理
                */
          }
     }
}
