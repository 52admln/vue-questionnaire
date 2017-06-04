import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/login/login'
import Layout from '@/components/layout/layout'
import List from '@/components/list/list'
import Edit from '@/components/edit/edit'
import View from '@/components/view/view'
import User from '@/components/user/user'
import Statis from '@/components/statis/statis'
import Result from '@/components/statis/result'
import crossAnalysis from '@/components/statis/cross-analysis'
import Error from '@/components/common/error/error'
import Complete from '@/components/common/complete/complete'

Vue.use(Router)

let router = new Router({
  routes: [
    {
      path: '/',
      name: 'index',
      redirect: '/platform'
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/complete',
      name: 'complete',
      component: Complete
    },
    {
      path: '/view/:id',
      name: 'View',
      component: View
    },
    {
      path: '/platform',
      name: 'Platform Layout',
      component: Layout,
      redirect: '/platform/list',
      children: [
        {
          path: 'list',
          name: 'Naire List',
          component: List,
          meta: {requiresAuth: true}
        },
        {
          path: 'edit',
          name: 'Edit Question',
          component: Edit,
          meta: {requiresAuth: true}
        },
        {
          path: 'edit/:id',
          name: 'Edit Detail',
          component: Edit,
          meta: {requiresAuth: true}
        },
        {
          path: 'user',
          component: User,
          meta: {requiresAuth: true}
        },
        {
          path: 'statis',
          name: 'Static',
          component: Statis,
          children: [
            {
              path: 'result/:id',
              name: 'Statis result',
              component: Result,
              meta: {requiresAuth: true}
            },
            {
              path: 'cross-analysis/:id',
              name: 'Statis analysis',
              component: crossAnalysis,
              meta: {requiresAuth: true}
            }
          ]
        }
      ]
    },
    {
      path: '/404',
      name: '404页面',
      component: Error
    },
    {
      path: '*',
      name: '未找到页面',
      redirect: '/404'
    }
  ]
})

// JWT 用户权限校验，判断 TOKEN 是否在 localStorage 当中
// 对象解构，等于 {name} === to.name
router.beforeEach((to, from, next) => {
  // 获取 JWT Token
  if (to.meta.requiresAuth) {
    // 判断该路由是否需要登录权限
    console.log('store token:' + localStorage.getItem('USER_NAME'))
    if (localStorage.getItem('USER_NAME')) {
      // 通过vuex state获取当前的token是否存在
      next()
    } else {
      next({
        path: '/login',
        query: {redirect: to.fullPath}
        // 将跳转的路由path作为参数，登录成功后跳转到该路由
      })
    }
  } else {
    next()
  }
})

export default router
