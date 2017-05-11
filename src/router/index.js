import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/login/login'
import Layout from '@/components/layout/layout'
import List from '@/components/list/list'
import Edit from '@/components/edit/edit'
import New from '@/components/edit/new'
import NewLayout from '@/components/edit/home'
import View from '@/components/view/view'

Vue.use(Router)

let router = new Router({
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: Login
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
          path: 'new',
          component: NewLayout,
          meta: {requiresAuth: true},
          children: [
            {
              path: '/',
              name: 'New Naire',
              component: New,
              meta: {requiresAuth: true}
            },
            {
              path: 'edit',
              name: 'Edit Question',
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
