import api from '../api/index'
import * as types from './mutation-types'

// 全局的actions

// questionList

export const getQuestionList = ({commit, state}) => {
  commit(types.REQUEST_QUESTION_LIST, {
    questionList: api.getQuestionList()
  })
  console.log('commit REQUEST')
}
