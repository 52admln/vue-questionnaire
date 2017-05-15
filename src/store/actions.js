import api from '../api/index'
import * as types from './mutation-types'

// 全局的actions

// questionList
export const getQuestionList = ({commit, state}) => {
  commit(types.REQUEST_QUESTION_LIST, {
    questionList: api.getQuestionList()
  })
  console.log('commit REQUEST_QUESTION_LIST')
}
// 新增问卷
export const createNaire = ({commit, state}, data) => {
  commit(types.CREATE_NEW_NAIRE, {
    naire: data
  })
  console.log('commit CREATE_NEW_NAIRE')
}
export const saveNewNaire = ({commit, state}, data) => {
  commit(types.SAVE_NEW_NAIRE, {
    naire: data
  })
  console.log('commit SAVE_NEW_NAIRE')
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
