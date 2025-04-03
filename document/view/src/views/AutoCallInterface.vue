<template>
  <main>
    <p class="T-first">自动回调接口</p>
    <p class="T-content">
      在Func类中有一个函数叫"AutoVerCallFunction"，这个函数可以自动验证当前浏览器最后一段路径名而实现回调对应类的函数。
    </p>
    <p class="T-content">可以搭配void函数层来使用。</p>
    <p class="T-content">下面通过两段代码来实现。</p>
    <p class="T-content">controller/index.php中</p>
    <el-input class="T-textarea" v-model="firstData" type="textarea" rows="7" />
    <p class="T-content">void/demo.php中</p>
    <el-input
      class="T-textarea"
      v-model="secondData"
      type="textarea"
      rows="14"
    />
    <p class="T-error">注意：函数名会被冲突！</p>
    <p class="T-content">
      定义好自动回调函数后，我们在浏览器继续访问接口->app\controller\index.php\demo
    </p>
    <p class="T-content">
      如果成功返回我们在void函数层中对应函数的执行，那么恭喜你成功了！
    </p>
  </main>
</template>
<script setup lang="ts">
import { ref } from "vue";

const firstData = ref(`
  <?php

  use app\\void\\demo;

  Func::AutoVerCallFunction(demo::class);
`);

const secondData = ref(`
    <?php

     nampespace app\\void;

     class demo
     {
        public function demo()
        {
            echo "测试自动回调函数";
        }
     }
`);
</script>
<style scoped lang="scss"></style>