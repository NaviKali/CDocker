<?php

namespace app\controller;

require_once '../../config/Base.php';
use tank\Func\Func;
use function tank\{VerificationInclude, Weclome};

Func::SingleVerCallFunction('GET', 'index', function () {
   Weclome();
});

Func::SingleVerCallFunction('GET', 'ceshi', function () {
}, VerificationInclude('index')['add']);
