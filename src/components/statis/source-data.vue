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
            <div class="line" ></div>
            <div class="source-data-table">
                <Table size="small"
                       :columns="columnsStatis"
                       :data="statisData.user_result"> </Table>
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
        statisData: {
          'naire': {
            'title': '',
            'intro': '',
            'deadline': 0
          }
        },
        columnsStatis: [
          {
            title: '编号',
            type: 'index',
            width: 200
          },
          {
            title: '姓名',
            key: 'u_name',
            fixed: 'left',
            ellipsis: false,
            width: 200
          },
          {
            title: '班级',
            key: 'u_class',
            width: 200
          },
          {
            title: '学号',
            key: 'u_number',
            width: 200
          }
        ],
        userResult: [],
        data7: [
          {
            'name': '推广名称1推广名称1推广名称1推广名称1',
            'fav': 0,
            'show': 7302,
            'weak': 5627,
            'signin': 1563,
            'click': 4254,
            'active': 1438,
            'day7': 274,
            'day30': 285285285285285285285285285285285285285285285285285285285,
            'tomorrow': 1727,
            'day': 558,
            'week': 4440,
            'month': 5610
          },
          {
            'name': '推广名称2',
            'fav': 0,
            'show': 4720,
            'weak': 4086,
            'signin': 3792,
            'click': 8690,
            'active': 8470,
            'day7': 8172,
            'day30': 5197,
            'tomorrow': 1684,
            'day': 2593,
            'week': 2507,
            'month': 1537
          },
          {
            'name': '推广名称3',
            'fav': 0,
            'show': 7181,
            'weak': 8007,
            'signin': 8477,
            'click': 1879,
            'active': 16,
            'day7': 2249,
            'day30': 3450,
            'tomorrow': 377,
            'day': 1561,
            'week': 3219,
            'month': 1588
          },
          {
            'name': '推广名称4',
            'fav': 0,
            'show': 9911,
            'weak': 8976,
            'signin': 8807,
            'click': 8050,
            'active': 7668,
            'day7': 1547,
            'day30': 2357,
            'tomorrow': 7278,
            'day': 5309,
            'week': 1655,
            'month': 9043
          },
          {
            'name': '推广名称5',
            'fav': 0,
            'show': 934,
            'weak': 1394,
            'signin': 6463,
            'click': 5278,
            'active': 9256,
            'day7': 209,
            'day30': 3563,
            'tomorrow': 8285,
            'day': 1230,
            'week': 4840,
            'month': 9908
          },
          {
            'name': '推广名称6',
            'fav': 0,
            'show': 6856,
            'weak': 1608,
            'signin': 457,
            'click': 4949,
            'active': 2909,
            'day7': 4525,
            'day30': 6171,
            'tomorrow': 1920,
            'day': 1966,
            'week': 904,
            'month': 6851
          },
          {
            'name': '推广名称7',
            'fav': 0,
            'show': 5107,
            'weak': 6407,
            'signin': 4166,
            'click': 7970,
            'active': 1002,
            'day7': 8701,
            'day30': 9040,
            'tomorrow': 7632,
            'day': 4061,
            'week': 4359,
            'month': 3676
          },
          {
            'name': '推广名称8',
            'fav': 0,
            'show': 862,
            'weak': 6520,
            'signin': 6696,
            'click': 3209,
            'active': 6801,
            'day7': 6364,
            'day30': 6850,
            'tomorrow': 9408,
            'day': 2481,
            'week': 1479,
            'month': 2346
          },
          {
            'name': '推广名称9',
            'fav': 0,
            'show': 567,
            'weak': 5859,
            'signin': 128,
            'click': 6593,
            'active': 1971,
            'day7': 7596,
            'day30': 3546,
            'tomorrow': 6641,
            'day': 1611,
            'week': 5534,
            'month': 3190
          },
          {
            'name': '推广名称10',
            'fav': 0,
            'show': 3651,
            'weak': 1819,
            'signin': 4595,
            'click': 7499,
            'active': 7405,
            'day7': 8710,
            'day30': 5518,
            'tomorrow': 428,
            'day': 9768,
            'week': 2864,
            'month': 5811
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
      createColumn () {
        const tempObj = {
          title: '',
          key: '',
          width: 200
        }
        this.statisData.question.forEach((item, index) => {
          tempObj.title = item.q_content
          tempObj.key = 'q_' + item.q_id
          this.columnsStatis.push(Object.assign({}, tempObj))
        })
      }
    },
    beforeCreate () {
      // 获取数据，push 到 chartOptions 中
      // 获取 问卷id
      this.$http.post('/api/naire/sourcedata', {
        n_id: this.$route.params.id
      })
        .then((response) => {
          console.log(response.data)

          if (response.data.err === 0) {
            this.statisData = Object.assign({}, response.data.data)
            this.createColumn()
            this.hasReady = true
          } else {
            this.$Message.error(response.data.data)
          }
          // 影响行数大于0
//          if (response.data.err === 0) {
//            this.statisData = Object.assign({}, response.data.data)
//            this.hasReady = true
//          } else {
//            this.$Message.error(response.data.data)
//          }
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

    .source-data-table {
        margin: 20px 0;
    }
</style>
