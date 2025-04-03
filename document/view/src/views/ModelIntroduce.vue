<template>
  <main>
    <p class="T-first">模型层介绍</p>
    <p class="T-content">
      模型层主要用来对表进行业务处理、字段处理、表操作等等。
    </p>
    <p class="T-content">
      使用模型层是必不可少的，它可以提升我们对表的操作空间。
    </p>
    <p class="T-content">
      下面是模型层样板。
    </p>
    <el-input
      class="T-textarea"
      v-model="firstData"
      type="textarea"
      rows="25"
    />
  </main>
</template>
<script lang="ts" setup>
import {ref} from 'vue'

const firstData = ref(`
<?php
/**
 * 模型层模拟
 */
namespace app\\model;

class index extends \\tank\\MD\\MD
{

        /**Key绑定 */
        public static $Key = "";
        /**Guid绑定 */
        public static $Guid = ["Guid字段名字", "Guid双向绑定字段/值"];
        /**显示字段 */
        public static $field = [
                '字段名字' => self::SHOW,
        ];
        /**写入字段 */
        public static $writefield = [
                '字段' => "中文名字"
        ];
        /**开启软删除 */
        public static $OpenSoftDelete = true;
        /**软删除字段 */
        public static $SoftDeleteField = null;
        /**开启其余字段写入 */
        public static $OpenOtherWriteField = true;
        /**其余字段写入 */
        public static $OtherWriteField = [
                'create' => "新建时间字段",
                'update' => "修改时间字段",
        ];
        /**开启业务姓名字段写入 */
        public static $UserNameWriteField = true;
        /* 默认业务姓名字段 */
        public const DEFAULTUSERNAME = 'User';
        /**默认业务姓名字段值 */
        public const DEFAULTUSERNAMEVALUE = "admin";
        /**
         * 添加前
         */
        public static function onBeforeCreate()
        {
        }
        /**
         * 添加后
         */
        public static function onAfterCreate()
        {
        }
        /**
         * 修改前
         */
        public static function onBeforeUpdate()
        {
        }
        /**
         * 修改后
         */
        public static function onAfterUpdate()
        {

        }
        /**
         * 删除前
         */
        public static function onBeforeDelete()
        {

        }
        /**
         * 删除后
         */
        public static function onAfterDelete()
        {

        }
        /**
         * 查询前
         */

        public static function onBeforeSelect()
        {

        }
        /**
         * 查询后
         */
        public static function onAfterSelect()
        {

        }

}
`);
</script>
<style scoped>
</style>