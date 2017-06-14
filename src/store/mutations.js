// 引入 api 接口
import * as types from './mutation-types'
/*
 const _naire = {
 n_id: '1',
 title: '问卷1',
 creattime: '2017-05-14 14:32:34',
 deadline: '2017-05-31 00:00:00',
 status: '0',
 intro: '问卷介绍11111',
 topic: [
 {
 q_id: '1',
 question: '题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1题目内容1',
 isRequired: '1',
 type: '单选',
 description: '',
 selectContent: '',
 additional: '',
 options: [
 {
 o_id: '1',
 content: '选项1',
 isAddition: '1'
 },
 {
 o_id: '2',
 content: '选项2',
 isAddition: '0'
 }
 ]
 },
 {
 q_id: '2',
 question: '多选题1多选题1多选1题多选1题多选题多1选题多选题多选题1多选题1',
 isRequired: '0',
 type: '多选',
 description: '问题描述11111111',
 selectMultipleContent: [],
 additional: '',
 options: [
 {
 o_id: '3',
 content: '选项33',
 isAddition: '0'
 },
 {
 o_id: '4',
 content: '选项44',
 isAddition: '0'
 }
 ]
 },
 {
 q_id: '5',
 question: '文本题目11文本题目文本题111目文本11题目文本题目文本题目222',
 isRequired: '1',
 type: '文本',
 description: '问题描述11111111',
 selectContent: ''
 }
 ]
 }
 */
export default {
  [types.REQUEST_QUESTION_LIST] (state, action) {
    console.log(state, action)
    // 获取mock数据
    let data = JSON.stringify(action.naire)
    state.naire = JSON.parse(data)
  },
  [types.CREATE_NEW_NAIRE] (state, data) {
    console.log('create new naire', data)
    // state.naire = data.naire
    state.naire = {...state.naire, ...data.naire}
  },
  [types.SAVE_NEW_NAIRE] (state, data) {
    console.log(state, data)
    // state.naire = action.naire
    state.naire = {...state.naire, ...data.naire}
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
  },
  [types.CHANGE_STATUS] (state, data) {
    console.log(state, data)
    state.status = data
  },
  [types.CHANGE_NAIRE_STATUS] (state, data) {
    console.log(state, data)
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
    console.log(payload.user)
    state.isAdmin = true
    localStorage.setItem('USER_NAME', payload.username)
    localStorage.setItem('JWT_TOKEN', payload.token)
  },
  [types.LOG_OUT] (state) {
    console.log('logout')
    state.isAdmin = false
    localStorage.removeItem('JWT_TOKEN')
    localStorage.removeItem('USER_NAME')
  }
}
