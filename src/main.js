// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'
import axios from 'axios'
import iView from 'iview'
import 'iview/dist/styles/iview.css'   // 使用 CSS
// import IEcharts from 'vue-echarts-v3/src/full.vue';

Vue.use(iView)

// http request 拦截器
axios.interceptors.request.use(
  config => {
    if (sessionStorage.JWT_TOKEN) {  // 判断是否存在token，如果存在的话，则每个http header都加上token
      config.headers.Authorization = `token ${sessionStorage.JWT_TOKEN}`
    }
    return config
  },
  err => {
    return Promise.reject(err)
  })

// http response 拦截器
axios.interceptors.response.use(
  response => {
    return response
  },
  error => {
    if (error.response) {
      switch (error.response.status) {
        case 401:
          // 返回 401 清除token信息并跳转到登录页面
          store.commit('LOG_OUT')
          router.replace({
            path: '/login',
            query: {redirect: router.currentRoute.fullPath}
          })
          this.$Message.error('非法访问，请重试！')
      }
    }
    return Promise.reject(error.response.data)   // 返回接口返回的错误信息
  })

Vue.prototype.$http = axios
Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  ...App
}).$mount('#app')
