<template>
  <div class="result">
    <h2>{{ statisData.naire.title }}</h2>
    <h4>{{ statisData.naire.intro }}</h4>
    <p>截止日期：{{ statisData.naire.deadline | timeFormat}}</p>
    <div class="line"></div>
    <!-- v-for -->
    <div class="question-item" v-for="(question, index) in statisData.questions">
      <div class="title">
        <h3>Q{{index + 1}}: {{question.content}}</h3>
        <h5>{{ question.desc }}</h5>
      </div>
      <!-- v-for 数据库返回相应格式 -->
      <Table size="small" :columns="columnsStatis" :data="question.options"></Table>
      <div class="echarts">
        <div :id="'chart-'+index" :style="{width: '100%', height: '400px'}"></div>
      </div>
    </div>
  </div>
</template>

<script>
  import echarts from 'echarts'
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
        },
        columnsStatis: [
          {
            title: '选项',
            key: 'content'
          },
          {
            title: '小计',
            key: 'count',
            width: '150'
          },
          {
            title: '百分比',
            key: 'percent',
            width: '150'
          }
        ],
        chartsOptions: [
          {
            questionTitle: 'Q1',
            Axis: ['A', 'B'],
            series: [5, 20]
          },
          {
            questionTitle: 'Q2',
            Axis: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
            series: [50, 100]
          }
        ]
      }
    },
    filters: {
      timeFormat (value) {
        return formatDate(value)
      }
    },
    methods: {
      drawChart (index) {
        let _chart = echarts.init(document.getElementById('chart-' + index))
        _chart.setOption({
          title: {
            text: this.chartsOptions[index].questionTitle,
            textStyle: {
              color: '#657180'
            }
          },
          grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
          },
          tooltip: {},
          color: ['#39f'],
          legend: {
            data: ['数量']
          },
          xAxis: {
            type: 'value',
            boundaryGap: [0, 0.01]
          },
          yAxis: {
            axisLabel: {
              interval: 0
            },
            data: this.chartsOptions[index].Axis
          },
          series: [{
            name: '数量',
            type: 'bar',
            data: this.chartsOptions[index].series
          }]
        })
      }
    },
    mounted () {
      // todo 通过 Ajax 获取数据
      // todo 如果字符串超过 30 个字符，变为 ...
      // 临时测试 数据长度为 2 个
      for (let i = 0; i < 2; i++) {
        this.drawChart(i)
      }
    },
    created () {
      // todo 获取数据，push 到 chartOptions 中
    }
  }
</script>

<style>
  .question-item .title {
    padding: 10px 0;
  }

  .echarts {
    margin: 10px 0;
    width: 100%;
    height: 100%;
  }

  .line {
    padding: 5px 0 10px 0;
    border-bottom: 2px dotted #eee;
  }
</style>
