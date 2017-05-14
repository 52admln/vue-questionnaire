// 引入 api 接口
// import * as types from '../mutation-types'

const state = {
  items: []
}
const getters = {
  // questionList: state => state.items
}

// commit -> mutations 同步
// dispatch -> actions 异步
const mutations = {
  // [types.REQUEST_QUESTION_LIST] (state, action) {
  //   console.log(state, action)
  //   state.items = action.questionList
  // }
}

export default {
  state,
  getters,
  mutations
}
