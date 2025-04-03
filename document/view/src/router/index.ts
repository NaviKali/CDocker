import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      component: () => import("../views/Home.vue"),
    },
    {
      path: "/catalogue",
      component: () => import("../views/Catalogue.vue"),
    },
    {
      path: "/firstinterface",
      component: () => import("../views/FirstInterface.vue"),
    },
    {
      path: "/autocallinterface",
      component: () => import("../views/AutoCallInterface.vue"),
    },
    {
      path: "/middleware",
      component: () => import("../views/Middleware.vue"),
    },
    {
      path: "/modelintroduce",
      component: () => import("../views/ModelIntroduce.vue"),
    },
    {
      path: "/bindanddefine",
      component: () => import("../views/BindAndDefine.vue"),
    },
    {
      path: "/modelevent",
      component: () => import("../views/ModelEvent.vue"),
    },
    {
      path: "/timestamp",
      component: () => import("../views/TimeStamp.vue"),
    },
    {
      path: "/softdelete",
      component: () => import("../views/SoftDelete.vue"),
    },
    {
      path: "/businessname",
      component: () => import("../views/BusinesSname.vue"),
    },
  ],
});

export default router;
