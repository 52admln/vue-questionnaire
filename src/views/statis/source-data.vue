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
            <div class="source-data-table">
                <Table size="small"
                       :stripe="true"
                       :columns="columnsStatis"
                       :data="curResult"></Table>
                <!-- 分页 -->
                <div style="margin: 10px;overflow: hidden">
                    <div style="float: right;" v-if="hasReady">
                        <Page :total="total" :current="currentPage" :page-size="pageSize"
                              @on-change="changePage"></Page>
                    </div>
                </div>
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
            title: '序号',
            type: 'index',
            width: 100
          },
          {
            title: '姓名',
            key: 'u_name',
            fixed: 'left',
            ellipsis: false,
            width: 100
          },
          {
            title: '班级',
            key: 'u_class',
            width: 100
          },
          {
            title: '学号',
            key: 'u_number',
            width: 120
          }
        ],
        curResult: []
      }
    },
    filters: {
      timeFormat (value) {
        return formatDate(value)
      }
    },
    methods: {
      changePage (curPage) {
        console.log(curPage)
        let offsetRows = this.pageSize * (curPage - 1) // 数据偏移量
        this.currentPage = curPage
        this.curResult = this.statisData.user_result.slice(offsetRows, this.pageSize + offsetRows)
      },
      // 根据返回的题目列表，创建表格表头
      createColumn () {
        const tempObj = {
          title: '',
          key: '',
          width: 200
        }
        this.statisData.question.forEach((item, index) => {
          tempObj.title = item.q_content
          tempObj.key = 'q_' + item.q_id // 表头与数据 采用 q_<q_id> 来关联
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
//          console.log(response.data)
          if (response.data.err === 0) {
            this.statisData = Object.assign({}, response.data.data)
            // 前端分页
            this.curResult = this.statisData.user_result.slice(0, this.pageSize)
            this.total = this.statisData.user_result.length // 总数据量
            this.createColumn() // 创建表头
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

    .source-data-table {
        margin: 20px 0;
    }
</style>
