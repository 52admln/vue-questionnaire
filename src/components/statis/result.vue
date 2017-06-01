<template>
  <div class="result" v-if="hasReady">
    <h2>{{ statisData.naire.title }}</h2>
    <p>
      <Tag type="border" :color="statisData.naire.status === 0 ? 'red' : 'green'">
        {{ statisData.naire.status === 0 ? '未发布' : '已发布' }}
      </Tag>
    </p>
    <p>创建日期： {{ statisData.naire.creattime | timeFormat }} | 截止日期：{{ statisData.naire.deadline | timeFormat}}</p>
    <div class="line"></div>
    <!-- v-for -->
    <div class="question-item" v-for="(question, index) in statisData.questions">
      <div class="title">
        <h3>Q{{index + 1}}: （{{ question.type }}）{{ question.question}}{{question.isRequired ? "（必填）" : "（选填）"}}</h3>
        <h5>{{ question.description }}</h5>
      </div>
      <!-- v-for 数据库返回相应格式 -->
      <Table size="small" :columns="columnsStatis" :data="question.options"
             v-if="question.type === '单选' || question.type === '多选'"></Table>
      <Table height="400" size="small" :columns="columnsStatisText" :data="question.answerList"
             v-if="question.type === '文本'"></Table>
      <h3 class="addtion-title" v-if="question.type === '单选' && question.addtionContent.length > 0">附加理由列表：</h3>
      <Table height="200" size="small" :columns="columnsAddtion" :data="question.addtionContent"
             v-if="question.type === '单选' && question.addtionContent.length > 0"></Table>
      <div class="echarts" v-if="question.type === '单选' || question.type === '多选'">
        <div :id="'chart-'+ question.q_id" :style="{width: '100%', height: '400px'}"></div>
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
        hasReady: false,
        chartIndex: 0,
        statisData: {
          'naire': {
            'title': '',
            'intro': '',
            'deadline': 0
          }
        },
        statisData1: {
          'naire': {
            'title': '问卷名称',
            'intro': '问卷描述',
            'deadline': 1495628024980
          },
          'questions': [
            {
              'content': '题目内容',
              'desc': '题目说明',
              'q_id': 57,
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
              'q_id': 58,
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
        columnsAddtion: [
          {
            title: '编号',
            type: 'index',
            width: 80
          },
          {
            title: '附加理由',
            key: 'content'
          }
        ],
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
        columnsStatisText: [
          {
            title: '编号',
            type: 'index',
            width: 80
          },
          {
            title: '提交内容',
            key: 'content'
          }
        ],
        chartsOptions: {
          '57': {
            questionTitle: 'Q1',
            Axis: ['A', 'B'],
            series: [5, 20]
          },
          '58': {
            questionTitle: 'Q2',
            Axis: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
            series: [50, 100]
          }
        }
      }
    },
    filters: {
      timeFormat (value) {
        return formatDate(value)
      }
    },
    methods: {
      drawChart (index) {
        console.log(index)
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
      },
      getChartsData (data) {
        // 题目，选项数组，选项的小计
        data.questions.forEach((item, quesIndex) => {
          if (item.type === '单选' || item.type === '多选') {
            let tempObj = {}
            tempObj.questionTitle = 'Q' + (quesIndex + 1)
            tempObj.Axis = []
            item.options.forEach((option, index) => {
              tempObj.Axis.push(option.content)
            })
            tempObj.series = item.charts
            this.chartsOptions[item.q_id] = Object.assign({}, tempObj)
            console.log(item.q_id)
          }
        })
      }
    },
    updated () {
      // todo 通过 Ajax 获取数据
      // todo 如果字符串超过 30 个字符，变为 ...

      this.statisData.questions.forEach((item, index) => {
        if (item.type === '单选' || item.type === '多选') {
          this.drawChart(item.q_id)
        }
      })
    },
    beforeCreate () {
      // todo 获取数据，push 到 chartOptions 中
      // 获取 问卷id
      this.$http.post('/api/naire/statis', {
        n_id: this.$route.params.id
      })
        .then((response) => {
          console.log(response.data)
          // 影响行数大于0
          if (response.data.err === 0) {
            this.statisData = Object.assign({}, response.data.data)
            this.getChartsData(response.data.data)
            this.hasReady = true
          } else {
            this.$Message.error('获取失败，请重试')
            this.$router.go(-1)
          }
        })
        .catch((error) => {
          console.log(error)
          this.$Message.error('修改失败，请重试')
        })
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
  .addtion-title {
    padding: 10px 0;
  }
</style>
