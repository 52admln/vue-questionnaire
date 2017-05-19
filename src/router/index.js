import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/login/login'
import Layout from '@/components/layout/layout'
import List from '@/components/list/list'
import Edit from '@/components/edit/edit'
import View from '@/components/view/view'
import User from '@/components/user/user'
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

export default router
