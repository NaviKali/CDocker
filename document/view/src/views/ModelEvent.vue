<template>
  <main>
    <p class="T-first">事件处理</p>
    <p class="T-content">事件分别为：</p>
    <el-table :data="eventList" border style="width: 100%">
      <el-table-column prop="event" label="事件" />
      <el-table-column prop="event_data" label="描述" />
    </el-table>
    <p class="T-content">利用好事件可以帮助你提高代码安全性。</p>
    <p class="T-main">事件的执行优先级最大。</p>
  </main>
</template>
<script lang="ts" setup>
import { ref } from "vue";

const eventList = ref<Array<{ event: String; event_data: String }>>([
  {
    event: "onBeforeCreate",
    event_data: "添加前",
  },
  {
    event: "onAfterCreate",
    event_data: "添加后",
  },
  {
    event: "onBeforeUpdate",
    event_data: "修改前",
  },
  {
    event: "onAfterUpdate",
    event_data: "修改后",
  },
  {
    event: "onBeforeDelete",
    event_data: "删除前",
  },
  {
    event: "onAfterDelete",
    event_data: "删除后",
  },
  {
    event: "onBeforeSelect",
    event_data: "查询前",
  },
  {
    event: "onAfterSelect",
    event_data: "查询后",
  },
]);
</script>
<style scoped>
</style>