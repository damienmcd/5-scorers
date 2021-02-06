import Vue from 'vue'
import VueRouter from 'vue-router'
import $store from '@/store/index'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import(/* webpackChunkName: "Home" */ '../views/Home.vue')
  },
  {
    path: '/admin',
    name: 'admin',
    component: () => import(/* webpackChunkName: "Admin" */ '../views/Admin.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import(/* webpackChunkName: "Dashboard" */ '../views/Dashboard.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/pick-scorers',
    name: 'pick-scorers',
    component: () => import(/* webpackChunkName: "PickScorers" */ '../views/PickScorers.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/current-game',
    name: 'current-game',
    component: () => import(/* webpackChunkName: "AllCurrentGamePicks" */ '../views/AllCurrentGamePicks.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/change-password',
    name: 'change-password',
    component: () => import(/* webpackChunkName: "ChangePassword" */ '../views/ChangePassword.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/404',
    alias: '*',
    name: 'notFound',
    component: () => import(/* webpachChunkName: "NotFound" */ '../views/404.vue')
  }
]

const router = new VueRouter({
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!$store.getters.user.loggedIn) {
      next({
        name: 'home'
      })
    } else {
      next()
    }
  } else {
    next()
  }
})

export default router
