<script setup lang="ts">
import { RouterLink, RouterView } from "vue-router";

const handleNodeClick = (data: any) => {
  if (data.href != undefined) {
    location.href = data.href;
  }
};

const data = [
  {
    label: "首页",
    href: "/",
  },
  {
    label: "目录说明",
    href: "/catalogue",
  },
  {
    label: "控制层",
    children: [
      {
        label: "第一个接口",
        href: "firstinterface",
      },
      {
        label: "自动回调接口",
        href: "autocallinterface",
      },
    ],
  },
  {
    label: "中间件",
    href: "/middleware",
  },
  {
    label: "模型层",
    children: [
      {
        label: "模型层介绍",
        href: "/modelintroduce",
      },
      {
        label: "绑定与定义",
        href: "/bindanddefine",
      },
      {
        label: "事件处理",
        href: "/modelevent",
      },
      {
        label: "时间戳",
        href: "/timestamp",
      },
      {
        label: "软删除",
        href: "/softdelete",
      },
      {
        label: "业务姓名",
        href: "/businessname",
      },
    ],
  },
];
</script>

<template>
  <div class="common-layout">
    <el-container>
      <el-container>
        <el-aside width="200px">
          <h2>菜单</h2>
          <el-tree
            style="max-width: 600px"
            :data="data"
            @node-click="handleNodeClick"
        /></el-aside>
        <el-main> <RouterView /> </el-main>
      </el-container>
    </el-container>
  </div>
</template>

<style scoped>
</style>
