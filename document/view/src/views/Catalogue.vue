<template>
  <main>
    <p class="T-first">目录说明</p>
    <el-input v-model="gueData" type="textarea"  :rows="27" />
  </main>
</template>
<script setup lang="ts">
import { ref, reactive } from "vue";

const gueData = ref(`
    .bat => bat脚本文件
    .do => 终端执行段文件
    .exec => 命令行
    app => 应用层
        common => 门面
            interface => 接口文件
            trait => 特征文件
        controller => 控制层
        middleware => 中间件
        model => 模型层
        verification => 验证场景
        void => 函数层
    config => 配置层
    document => 文档
        view => vue3框架文档
        docker => docker虚拟容器文档
    env => 环境变量层
    logs => 日志
    proxy => 代理
        Apache2.4.39 => Apache代理
        nginx => nginx代理
    public => 公共文件
        app => 入口文件
        cloud => 云文件
        static => 静态文件
        then => (tank)替换页面
        upload => 上传文件
    views => 视图层
    app.json => 应用Json(可以自己存储数据)
    init.php => 初始化PHP启动项
`);
</script>

<style lang="scss" scoped>
</style>
