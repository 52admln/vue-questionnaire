// 引入 api 接口
import * as types from './mutation-types'

export default {
  [types.REQUEST_QUESTION_LIST] (state, action) {
    // 获取mock数据
    let data = JSON.stringify(action.naire)
    state.naire = JSON.parse(data)
  },
  [types.CREATE_NEW_NAIRE] (state, data) {
    // state.naire = data.naire
    state.naire = {...state.naire, ...data.naire}
  },
  [types.SAVE_NEW_NAIRE] (state, data) {
    // state.naire = action.naire
    state.naire = {...state.naire, ...data.naire}
  },
  [types.ADD_NEW_QUESTION] (state, action) {
    state.naire.topic.push(action.topic)
  },
  [types.DEL_QUESTION] (state, index) {
    state.naire.topic.splice(index, 1)
  },
  [types.DEL_OPTION] (state, data) {
    state.naire.topic[data.index].options.splice(data.opIndex, 1)
  },
  [types.CHANGE_STATUS] (state, data) {
    state.status = data
  },
  [types.CHANGE_NAIRE_STATUS] (state, data) {
    state.naire.status = data
  },
  [types.UPDATE_TITLE] (state, data) {
    // state.title = data
    state.naire = {...state.naire, title: data}
  },
  [types.UPDATE_INTRO] (state, data) {
    // state.intro = data
    state.naire = {...state.naire, intro: data}
  },
  [types.UPDATE_DEADLINE] (state, data) {
    // state.deadline = data
    state.naire = {...state.naire, deadline: data}
  },
  [types.USER_LOGIN] (state, payload) {
    state.isAdmin = true
    sessionStorage.setItem('USER_NAME', payload.username)
    sessionStorage.setItem('JWT_TOKEN', payload.token)
  },
  [types.LOG_OUT] (state) {
    state.isAdmin = false
    sessionStorage.removeItem('JWT_TOKEN')
    sessionStorage.removeItem('USER_NAME')
  }
}
