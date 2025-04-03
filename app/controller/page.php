<?php

namespace app\controller;

require_once '../../config/Base.php';
require_once '../../vendor/autoload.php';
use tank\Func\Func;
use app\void\Page as VoidPage;

Func::AutoVerCallFunction(VoidPage::class);