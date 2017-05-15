import Vue from 'vue'
import Vuex from 'vuex'
import * as actions from './actions'
import naire from './modules/naire'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
  // 将各组件分别模块化存入 Store
  modules: {
    naire
  },
  actions,
  strict: debug
})
