// 引入 api 接口
import * as types from '../mutation-types'

const state = {
  naire: {}
}
const getters = {
  naire: state => state.naire,
  questionList: (state) => {
    return JSON.stringify(state.naire.topic)
  }
}

// commit -> mutations 同步
// dispatch -> actions 异步
const mutations = {
  [types.REQUEST_QUESTION_LIST] (state, action) {
    console.log(state, action)
    state.items = state.naire.topic
  },
  [types.CREATE_NEW_NAIRE] (state, action) {
    console.log(state, action)
    state.naire = action.naire
  },
  [types.ADD_NEW_QUESTION] (state, action) {
    console.log(state, action)
    state.naire.topic.push(action.topic)
  },
  [types.DEL_QUESTION] (state, index) {
    console.log(state, index)
    state.naire.topic.splice(index, 1)
  },
  [types.DEL_OPTION] (state, data) {
    console.log(state, data)
    state.naire.topic[data.index].options.splice(data.opIndex, 1)
  }
}

export default {
  state,
  getters,
  mutations
}
