<template>
  <main>
    <p class="T-first">第一个接口</p>
    <p class="T-content">
      在浏览器上进入到app\controller\index.php中，会出现Tank框架的欢迎语。
    </p>
    <p class="T-content">
      假如在浏览器路径后面加上/index看看会如何呢？效果是不是一样？那如果是/ceshi呢?
    </p>
    <el-card style="max-width: 480px" class="T-Card">
      <p>本地路径/app/controller/index.php/index</p>
      <p>本地路径/app/controller/index.php/ceshi</p>
    </el-card>
    <p class="T-content">
      如果看到的内容是截然不同的话，那就可以去查看app\controller\index.php的PHP文件代码了，看看代码是如何实现的吧！
    </p>
    <p class="T-content">在文件中，我们定义了下面这些代码。</p>
    <el-input
      class="T-textarea"
      v-model="firstData"
      type="textarea"
      rows="15"
    />
    <p class="T-content">
      SingleVerCallFunction这个函数类似于Router，你可以仔细的发现，这就是个验证路径执行回调函数，
    </p>
    <p class="T-content">参数:[请求类型，路径名，执行回调函数，验证场景{可选}]</p>
    <p class="T-content">
      当我们理解这个函数的作用和用法的时候，就可以自己去调用来实现一个自己的接口。
    </p>
    <el-input
      class="T-textarea"
      v-model="secondeData"
      type="textarea"
      rows="3"
    />
    <p class="T-content">
        这里我们新建一个以demo为路径名的回调函数，然后我们去路径上修改最后一个路径为/demo来看看效果。
    </p>
    <p class="T-content">
        如果最后的结果是显示 "测试接口" 的话，那么你就成功理解了。
    </p>
  </main>
</template>
<script setup lang="ts">
import { ref } from "vue";

const firstData = ref(`<?php
namespace app\\controller;

require_once '../../config/Base.php';
use tank\\Func\\Func;
use function tank\\{VerificationInclude, Weclome};

Func::SingleVerCallFunction('GET', 'index', function () {
   Weclome();
});

Func::SingleVerCallFunction('GET', 'ceshi', function () {
}, VerificationInclude('index')['add']);
`);

const secondeData =
  ref(`Func::SingleVerCallFunction('GET', 'demo', function () {
    echo "测试接口";
});`);
</script>
<style scoped lang="scss"></style>