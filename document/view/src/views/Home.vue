<template>
  <main>
    <p class="T-first">欢迎使用观看Tank框架文档</p>
    <p class="T-content">
        介绍一下框架的历史和作者
    </p>
    <el-card class="T-Card" style="max-width: 480px">
        <p>框架的历史：创立于2023年11月20日</p>
        <p>框架的作者：一位普通高中生[高二]</p>
    </el-card>
    <p>
    </p>
    <p class="T-content">接下来介绍一下框架的特点。</p>
    <el-card class="T-Card" style="max-width: 480px">
      <p
        v-for="(item, index) in IntroductionData"
        :key="index"
        class="text item"
      >
        <RouterLink style="text-decoration: unset;" :to="item.to">{{ item.data }}</RouterLink>
      </p>
    </el-card>
    <p class="T-content">虽然页面很简陋，但是我会不断跟进该框架的!</p>
  </main>
</template>
<script setup lang="ts">
import { ref } from "vue";
import { RouterLink } from "vue-router";

const IntroductionData = ref([
  {
    data: "入口文件[“更快的调用接口函数”][多应用]",
    to: "",
  },
  {
    data: "视图层写法",
    to: "",
  },
  {
    data: "提供业务中间件",
    to: "",
  },
  {
    data: "提供需求的本地类",
    to: "",
  },
  {
    data: "环境变量",
    to: "",
  },
  {
    data: "提供代理[Apache+nginx]",
    to: "",
  },
]);
</script>
<style scoped lang="scss">
</style>