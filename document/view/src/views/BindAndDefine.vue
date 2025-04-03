<template>
  <main>
    <p class="T-first">绑定与定义</p>
    <p class="T-content">我们先来简介一下绑定。</p>
    <p class="T-content">这里的绑定分为：key绑定和guid绑定</p>
    <p class="T-content">Key绑定</p>
    <p class="T-content">Key绑定属于id迭代式绑定</p>
    <p class="T-error">可能会被冲突，关联不建议使用id关联，尽量使用guid！</p>
    <el-input class="T-textarea" v-model="firstData" type="textarea" rows="3" />
    <p class="T-content">Guid绑定</p>
    <p class="T-content">Guid绑定属于编码后的数据</p>
    <el-input
      class="T-textarea"
      v-model="secondData"
      type="textarea"
      rows="3"
    />
    <p class="T-content">下面说的定义了。</p>
    <p class="T-content">
      显示字段是查询后返回的数据显示某些字段，可以被field函数重新定义显示字段。
    </p>
    <el-input class="T-textarea" v-model="thirdData" type="textarea" rows="5" />
    <p class="T-content">
      写入字段是调用模型创造[Modelcreate]的时候需要给予的字段数据，这个必须重视。
    </p>
    <el-input class="T-textarea" v-model="fourData" type="textarea" rows="5" />
  </main>
</template>
<script lang="ts" setup>
import { ref } from "vue";

const fourData = ref(`
public static $writefield = [
                'test_id' => "测试id"
];
`);

const thirdData = ref(`
public static $field = [
                'test_id' => self::SHOW,//SHOW->显示，HIDDEN->消失
];
`);

const firstData = ref(`
public static $Key = "test_id";
`);

const secondData = ref(`
public static $Guid = ["test_guid", 双向绑定的值取决于写入字段];
`);
</script>
<style scoped>
</style>