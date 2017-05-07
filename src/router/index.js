import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/login/login'
import Layout from '@/components/layout/layout'
import List from '@/components/list/list'
import Edit from '@/components/edit/edit'
import New from '@/components/edit/new'
import NewLayout from '@/components/edit/home'

Vue.use(Router)

let router = new Router({
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/platform',
      name: 'Layout',
      component: Layout,
      redirect: '/platform/list',
      children: [
        {
          path: 'list',
          name: 'list',
          component: List,
          meta: {requiresAuth: true}
        },
        {
          path: 'new',
          name: 'new',
          component: NewLayout,
          meta: {requiresAuth: true},
          children: [
            {
              path: '/',
              component: New,
              meta: {requiresAuth: true}
            },
            {
              path: 'edit',
              component: Edit,
              meta: {requiresAuth: true}
            }
          ]
        }
      ]
    }
  ]
})

export default router
