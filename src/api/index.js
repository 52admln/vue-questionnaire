const _questionlist = [
  {
    question: '问题1',
    order: 1,
    q_id: 1,
    isRequired: true,
    type: '单选',
    selectContent: '',
    additional: '',
    options: [
      {
        content: '选项1',
        o_id: 1,
        isAddition: true
      },
      {
        content: '选项1',
        o_id: 2,
        isAddition: false
      }
    ]
  },
  {
    question: '多选问题2',
    order: 2,
    q_id: 2,
    isRequired: true,
    type: '多选',
    selectMultipleContent: [],
    options: [
      {
        content: '选项1',
        o_id: 3,
        isAddition: false
      },
      {
        content: '选项1',
        o_id: 4,
        isAddition: false
      }
    ]
  },
  {
    question: '多选多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3问题3',
    order: 22,
    q_id: 211,
    isRequired: true,
    type: '多选',
    selectMultipleContent: [],
    options: [
      {
        content: '选项1',
        o_id: 223,
        isAddition: false
      },
      {
        content: '选项1',
        o_id: 554,
        isAddition: false
      }
    ]
  },
  {
    question: '文本题3',
    order: 3,
    q_id: 3,
    isRequired: true,
    type: '文本',
    selectContent: ''
  }
]

export default {
  // quesitonList
  getQuestionList () {
    console.log(_questionlist)
    return _questionlist
  }
}
