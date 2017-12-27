import Vue from 'vue'
import Router from 'vue-router'
const Login = () => import('@/views/login/login')
// const Layout = () => import('@/views/layout/layout')
import Layout from '@/views/layout/layout'

const List = () => import('@/views/list/list')
const Edit = () => import('@/views/edit/edit')
const View = () => import('@/views/view/view')
const User = () => import('@/views/user/user')
const ChangePwd = () => import('@/views/admin/changepwd')

const Statis = () => import('@/views/statis/statis')
const Result = () => import('@/views/statis/result')
const crossAnalysis = () => import('@/views/statis/cross-analysis')
const SourceData = () => import('@/views/statis/source-data')

const Error = () => import('@/views/common/error/error')
const Complete = () => import('@/views/common/complete/complete')

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
    if (localStorage.getItem('JWT_TOKEN')) {
      // 通过获取当前的token是否存在
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
