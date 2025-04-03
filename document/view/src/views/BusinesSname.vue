<template>
  <main>
    <p class="T-first">业务姓名</p>
    <p class="T-content">业务姓名是为了记录是由谁来进行操作的用户。</p>
    <p class="T-main">建议定义用户名的简写。</p>
    <el-input class="T-textarea" v-model="firstData" type="textarea" rows="8" />
    <p class="T-content">默认值是为了提供参考，如果不想进行设置业务姓名值的话也可以不填。</p>
  </main>
</template>
<script lang="ts" setup>
import { ref } from "vue";

const firstData = ref(`
        /**开启业务姓名字段写入 */
        public static $UserNameWriteField = true;
        /* 默认业务姓名字段 */
        public const DEFAULTUSERNAME = 'User';
        /**默认业务姓名字段值 */
        public const DEFAULTUSERNAMEVALUE = "admin";
`);
</script>
<style scoped>
</style>