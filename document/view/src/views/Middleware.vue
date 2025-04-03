<template>
  <main>
    <p class="T-first">中间件</p>
    <p class="T-content">
      中间件是一种拦截器功能的函数验证，你可以进行任何的身份验证，算法验证等等，来确保安全性。
    </p>
    <p class="T-content">
      Tank提供了一些中间件，如果你觉得需要的话你可以开启。
    </p>
    <el-collapse
      style="margin-top: 20px; margin-bottom: 20px"
      v-model="activeNames"
      @change="handleChange"
      accordion
    >
      <el-collapse-item
        v-for="(item, index) in collapseList"
        :key="index"
        :title="item.title"
      >
        <div style="letter-spacing: 2px">
          {{ item.content }}
        </div>
      </el-collapse-item>
    </el-collapse>
    <p class="T-content">下面正式简介中间件使用。</p>
    <p class="T-content">
      在app/middleware/middleware.php中，我们可以自己写一个属于自己的中间件。
    </p>
    <el-input
      class="T-textarea"
      v-model="firstData"
      type="textarea"
      rows="14"
    />
    <p class="T-content">我们可以看见，我们必须要写在Handle函数里面，这是一个处理数据的函数，我们可以根据任何条件来进行判断，如果错误可以进行<a href="/">抛出异常</a>。</p>
  </main>
</template>
<script lang="ts" setup>
import { ref, onMounted } from "vue";

const firstData = ref(`
 <?php
namespace app\\middleware;

use function tank\\BathVerParams;

/**
 * 中间件
 */
class middleware
{
        /**
         * 中间件定义
         * @static
         * @param array $params 接受参数
         * @param array $request 请求头
         * @param array $response 响应头
         * @return mixed
         */
        public static function Handle(array $params,array $request,array $response)
        {
        }
}

`);

const collapseList = ref<Array<{ title: String; content: String }>>([
  {
    title: "localareanetworkrestrictionmiddleware(本地IP限制接口访问)",
    content: `
        有的时候在局域网里面，为了规划每个IP成员要进行对应的接口访问操作，而不是导致访问另外的接口从而导致报错或者数据错误、数据缺失等等的情况。
    `,
  },
  {
    title: "loginmiddleware(登录验证器)",
    content: `在其他网站的时候，一般都会看见密码登录要8位长度或者不能包含a-z字母的等等情况。这些都是确保密码统一制，不仅要前端验证，更是要后端进行验证。`,
  },
  {
    title: "phpversionmiddleware(PHP版本验证)",
    content: `PHP版本验证是为了更好的使用某版本的新特征或者新函数、类，以至于确保要符合能够使用PHP自带的函数即可，另一方面是为了版本统一。`,
  },
  {
    title: "routerunreal(路由虚幻)",
    content: `
        在某些网站搜索中，你能看见浏览器路径会多出很多GET请求参数，但是这些都是没多大作用的，这属于一种视觉差，如果启用路由虚幻，那么你将需要抛弃GET请求传参，从而使用POST传参。
    `,
  },
  {
    title: "sessionmiddleware(Session会话)",
    content: `这个中间件推荐开启，因为有时候会话可以帮助我们多存储一些临时数据。`,
  },
  {
    title: "tokenmiddleware(Token验证器)",
    content: `Token是一种权限验证码，他的作用是验证你是否有权限进行接口访问，推荐开启该中间件。`,
  },
  {
    title: "useragentmiddleware(浏览器用户代理)",
    content: `这个中间件暂时属于一种未完善的状态，但是可以禁止Edge浏览器访问。`,
  },
]);
</script>
<style scoped>
</style>