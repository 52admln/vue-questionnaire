// axios 后端接口
// 引入vue
import Vue from 'vue'
// 引入axios
import axios from 'axios'
// 建立 user question的接口 axios promise
// return promise 对象

export default {
  // 获取问卷详情
  getNaire () {
    return axios('/api/naire/detail')
  }
}
