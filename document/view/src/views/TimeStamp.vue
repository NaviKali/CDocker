<template>
  <main>
    <p class="T-first">时间戳</p>
    <p class="T-content">
      时间戳是指当我们创建或修改或删除某一条数据时，获取当前时间写入对应操作字段中。
    </p>
    <el-input
      class="T-textarea"
      v-model="firstData"
      type="textarea"
      rows="9"
    />
    <p class="T-content">
      如果不想写入时间戳可以把$OpenOtherWriteField中的true改为false，也就是关闭。
    </p>
    <p class="T-error">注意！字段名称不能一样。</p>
  </main>
</template>
<script lang="ts" setup>
import { ref } from "vue";

const firstData = ref(`
         /**开启其余字段写入 */
        public static $OpenOtherWriteField = true;
         /**其余字段写入 */
        public static $OtherWriteField = [
                'create' => "create_datetime",
                'update' => "update_datetime",
        ];
`);
</script>
<style scoped>
</style>