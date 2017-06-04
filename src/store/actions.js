// import api from '../api/index'
import * as types from './mutation-types'
import axios from 'axios'

// 全局的actions
// todo 管理员登录时，将 is_admin 设置为 true

// questionList
export const getNaire = ({commit, state}, data) => {
  console.log('commit REQUEST_QUESTION_LIST')
  return axios.post('/api/naire/detail', {
    n_id: data.n_id
  })
}
// 新增问卷
export const createNaire = ({commit, state}, data) => {
  commit(types.CREATE_NEW_NAIRE, {
    naire: data
  })
  console.log('commit CREATE_NEW_NAIRE')
}
export const saveNewNaire = ({commit, state}, data) => {
  // const params = new URLSearchParams()
  // params.append('naire', JSON.stringify(state.naire))
  // params.append('status', state.status)
  console.log('commit SAVE_NEW_NAIRE')
  return axios.post('/api/naire/save', {
    naire: state.naire,
    status: state.status
  })
}

// 新增问题
export const addQuestion = ({commit, state}, data) => {
  commit(types.ADD_NEW_QUESTION, {
    topic: data
  })
  console.log('commit ADD_NEW_QUESTION')
}

export const delQuestion = ({commit, state}, data) => {
  commit(types.DEL_QUESTION, data)
  console.log('commit DEL_QUESTION')
}

export const delOption = ({commit, state}, data) => {
  commit(types.DEL_OPTION, data)
  console.log('commit DEL_OPTION')
}

export const changeStatus = ({commit, state}, data) => {
  commit(types.CHANGE_STATUS, data)
  console.log('commit CHANGE_STATUS')
}

export const changeNaireStatus = ({commit, state}, data) => {
  commit(types.CHANGE_NAIRE_STATUS, data)
  console.log('commit CHANGE_NAIRE_STATUS')
}

export const login = ({commit}, data) => {
  console.log('commit USER_LOGIN')
  commit(types.USER_LOGIN, {
    username: data.username,
    token: data.token
  })
}

export const logout = ({commit}) => {
  console.log('commit LOG_OUT')
  commit(types.LOG_OUT)
}
