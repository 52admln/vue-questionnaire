import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/views/login/login'
import Layout from '@/views/layout/layout'

import List from '@/views/list/list'
import Edit from '@/views/edit/edit'
import View from '@/views/view/view'
import User from '@/views/user/user'
import ChangePwd from '@/views/admin/changepwd'

import Statis from '@/views/statis/statis'
import Result from '@/views/statis/result'
import crossAnalysis from '@/views/statis/cross-analysis'
import SourceData from '@/views/statis/source-data'

import Error from '@/views/common/error/error'
import Complete from '@/views/common/complete/complete'

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
          path: 'admin',
          component: ChangePwd,
          meta: {requiresAuth: true}
        },
        {
          path: 'statis',
          name: 'Static',
          component: Statis,
          children: [
            {
              path: 'result/:id',
              name: '结果统计',
              component: Result,
              meta: {requiresAuth: true}
            },
            {
              path: 'source/:id',
              name: '样本数据',
              component: SourceData,
              meta: {requiresAuth: true}
            },
            {
              path: 'cross-analysis/:id',
              name: '交叉分析',
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
