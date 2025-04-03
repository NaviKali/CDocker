<template>
  <main>
    <p class="T-first">软删除</p>
    <p class="T-content">
      软删除是当你执行删除的时候，会进行删除的字段写入，而不是直接删除该数据，查询的时候也不会查询删除字段带时间戳的数据。
    </p>
    <p class="T-content">总体来说就是数据还在，但是无法查询。</p>
    <el-input class="T-textarea" v-model="firstData" type="textarea" rows="6" />
    <p class="T-content">当然你也可以选择关闭它，关闭后的删除就是真实删除了。</p>
  </main>
</template>
<script lang="ts" setup>
import { ref } from "vue";

const firstData = ref(`
        /**开启软删除 */
        public static $OpenSoftDelete = true;
        /**软删除字段 */
        public static $SoftDeleteField = delete_datetime;
`);
</script>
<style scoped>
</style>
