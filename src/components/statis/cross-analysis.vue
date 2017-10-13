<template>
    <div>
        <Spin v-if="!hasReady">
            <Icon type="load-c"
                  size=18
            class="demo-spin-icon-load"></Icon>
            <div>数据加载中...</div>
        </Spin>
        <div class="source-data" v-if="hasReady">
            <h2>{{ statisData.naire.title }}</h2>
            <p>
                <Tag type="border" :color="statisData.naire.status === 0 ? 'red' : 'green'">
                    {{ statisData.naire.status === 0 ? '未发布' : '已发布' }}
                </Tag>
            </p>
            <p>创建日期： {{ statisData.naire.creattime | timeFormat
                }} | 截止日期：{{ statisData.naire.deadline | timeFormat}}</p>
            <div class="line"></div>
            <Row class="option-select">
                <Col span="12">
                <h3>自变量：</h3>
                <p>一般为样本数据，例如性别和年龄，目前支持一项。</p>
                <Select v-model="options.x_id" style="width:100%;padding-right: 20px">
                    <Option v-for="item in options.select" :value="item.value" :key="item.value">{{ item.label }}
                    </Option>
                </Select>
                </Col>
                <Col span="12">
                <h3>因变量：</h3>
                <p>您要分析的目标题目，目前支持一项。</p>
                <Select v-model="options.y_id" style="width:100%;padding-right: 20px">
                    <Option v-for="item in options.select" :value="item.value" :key="item.value">{{ item.label }}
                    </Option>
                </Select>
                </Col>
            </Row>
            <div class="btn">
                <Button type="primary" icon="ios-shuffle" @click="crossAnalysis">交叉分析</Button>
            </div>
            <div class="source-data-table">
                <Table size="small"
                       :stripe="true"
                       :columns="columnsStatis"
                       :data="curResult" v-show="tableDisplay"></Table>
            </div>

        </div>
    </div>
</template>

<script>
  import { formatDate } from '../../common/js/utils'

  export default {
    data () {
      return {
        hasReady: false,
        currentPage: 1,
        total: 5,
        pageSize: 10,
        statisData: {
          'naire': {
            'title': '',
            'intro': '',
            'deadline': 0
          }
        },
        columnsStatis: [
          {
            title: 'X \\ Y',
            key: 'type',
            ellipsis: false,
            width: 100
          },
          {
            title: '小计',
            key: 'count',
            fixed: 'right',
            ellipsis: false,
            width: 100
          }
        ],
        curResult: [],
        tableDisplay: false,
        options: {
          x_id: '',
          y_id: '',
          select: []
        }
      }
    },
    filters: {
      timeFormat (value) {
        return formatDate(value)
      }
    },
    methods: {
      GetRound (num, len) {
        return Math.round(num * Math.pow(10, len)) / Math.pow(10, len)
      },
      // 格式化数据
      formateData () {
        let _temp = [] // 存放表头
        let _obj = {} // 一行的临时数据
        let curIndex = 0
        let _rowSum = 0 // 一行的小计
        let _sum = 0 // 一行的小计
        let sum = []
        this.statisData.cross_result.forEach((item, index) => {
          curIndex++
          _rowSum += item.count * 1
          if (curIndex >= this.statisData.column.length || index === this.statisData.cross_result.length - 1) {
            sum[item.x_id] = _rowSum
            _obj = {}
            curIndex = 0
            _rowSum = 0
          }
        })
        this.statisData.cross_result.forEach((item, index) => {
          console.log(item)
          curIndex++
          _obj.type = item.x_value
          // this.GetRound(((item.count / sum[item.x_id])
          _obj['y_' + item.y_id] = `${item.count}`
          _sum += item.count * 1
          if (curIndex >= this.statisData.column.length || index === this.statisData.cross_result.length - 1) {
            _obj.count = _sum
            _temp.push(Object.assign({}, _obj))
            _obj = {}
            curIndex = 0
            _sum = 0
          }
        })
        console.log(_temp)
        this.curResult = _temp
      },
      // 根据返回的题目列表，创建表格表头
      createColumn () {
        this.columnsStatis = [
          {
            title: 'X \\ Y',
            key: 'type',
            ellipsis: false,
            width: 100
          },
          {
            title: '小计',
            key: 'count',
            fixed: 'right',
            ellipsis: false,
            width: 100
          }
        ]
        const tempObj = {
          title: '',
          key: ''
        }
        this.statisData.column.forEach((item, index) => {
          tempObj.title = item.o_value
          tempObj.key = 'y_' + item.o_id // 表头与数据 采用 q_<q_id> 来关联
          this.columnsStatis.push(Object.assign({}, tempObj))
        })
      },
      crossAnalysis () {
        // 获取数据，push 到 chartOptions 中
        // 获取 问卷id
        this.$http.post('/api/naire/crossanalysis', {
          n_id: this.$route.params.id,
          x_id: this.options.x_id,
          y_id: this.options.y_id
        })
          .then((response) => {
            console.log(response.data)
            if (response.data.err === 0) {
              this.statisData.cross_result = response.data.data.cross_result
              this.statisData.column = response.data.data.column
              this.createColumn() // 创建表头
              this.formateData() // 格式化表表格数据
              this.tableDisplay = true
            } else {
              this.$Message.error(response.data.data)
            }
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('获取数据错误，请重试')
          })
      }
    },
    beforeCreate () {
      this.$http.post('/api/naire/questions', {
        n_id: this.$route.params.id
      })
        .then((response) => {
          console.log(response.data)
          if (response.data.err === 0) {
            this.statisData.naire = response.data.data.naire
            this.options.select = response.data.data.questions
            this.hasReady = true
          } else {
            this.$Message.error(response.data.data)
          }
        })
        .catch((error) => {
          console.log(error)
          this.$Message.error('获取数据错误，请重试')
        })
    }
  }
</script>

<style scoped>
    /* loading style */

    .demo-spin-icon-load {
        animation: ani-demo-spin 1s linear infinite;
    }

    @keyframes ani-demo-spin {
        from {
            transform: rotate(0deg);
        }
        50% {
            transform: rotate(180deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .demo-spin-col {
        height: 100px;
        position: relative;
        border: 1px solid #eee;
    }

    .source-data-table, .option-select {
        margin: 20px 0;
    }
</style>
