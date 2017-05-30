<template>
  <div class="result">
    <h2>{{ statisData.naire.title }}</h2>
    <h4>{{ statisData.naire.intro }}</h4>
    <p>截止日期：{{ statisData.naire.deadline | timeFormat}}</p>
    <div class="line"></div>
  </div>
</template>

<script>
  // import echarts from 'echarts'
  import { formatDate } from '../../common/js/utils'

  export default {
    data () {
      return {
        statisData: {
          'naire': {
            'title': '问卷名称',
            'intro': '问卷描述',
            'deadline': 1495628024980
          },
          'questions': [
            {
              'content': '题目内容',
              'desc': '题目说明',
              'options': [
                {
                  'content': '选项1',
                  'count': 30,
                  'percent': 50
                },
                {
                  'content': '选项2',
                  'count': 15,
                  'percent': 60
                }
              ],
              'optionlist': ['衬衫', '羊毛衫'],
              'charts': [30, 15]
            },
            {
              'content': '题目内容',
              'desc': '题目说明',
              'options': [
                {
                  'content': '选项1',
                  'count': 30,
                  'percent': 50
                },
                {
                  'content': '选项2',
                  'count': 15,
                  'percent': 60
                }
              ],
              'optionlist': ['衬衫', '羊毛衫'],
              'charts': [30, 15]
            }
          ]
        }
      }
    },
    filters: {
      timeFormat (value) {
        return formatDate(value)
      }
    },
    methods: {
    },
    mounted () {
    },
    created () {
    }
  }
</script>

<style>
  .question-item .title {
    padding: 10px 0;
  }

  .line {
    padding: 5px 0 10px 0;
    border-bottom: 2px dotted #eee;
  }
</style>
