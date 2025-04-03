<?php
/**
 * (配置)响应头
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Credentials: true");
header("Service-Frame:Tank");
header("Service-Date:".date("Y-m-d h:i:s"));