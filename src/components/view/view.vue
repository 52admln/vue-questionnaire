<template>
  <div class="view-layout">
    <Spin size="large" fix v-if="spinShow"></Spin>
    <div class="main">
      <div class="header">
        <h1>{{naire.title}}</h1>
      </div>
      <div class="content">
        <div class="intro">
          <p>问卷介绍：{{naire.intro}}</p>
          <p>截止日期：{{naire.deadline | timeFormat}}</p>
        </div>
        <questionList :question-list="this.naire.topic">
          <Row type="flex" justify="center" align="middle" class="code-row-bg">
            <Button type="success"
                    v-if="isAdmin"
                    @click="goBack">返回管理平台
            </Button>
            <Button type="primary"
                    @click="submitNaire">提交问卷
            </Button>
          </Row>
        </questionList>
      </div>
      <div class="footer">
        <p>Timu蜗壳工作室</p>
      </div>
    </div>
  </div>
</template>

<script>
  import questionList from '@/components/common/questionList/questionList'
  import { formatDate } from '../../common/js/utils'

  export default {
    data () {
      return {
        naire: {
          topic: []
        },
        spinShow: true
      }
    },
    filters: {
      timeFormat (value) {
        return formatDate(value)
      }
    },
    watch: {
      // 如果路由有变化，会再次执行该方法
      '$route': 'fetchData'
    },
    methods: {
      error (nodesc, title, desc) {
        this.$Notice.error({
          title: title,
          desc: nodesc ? '' : desc
        })
      },
      fetchData () {
        console.log(this.$route.params.id)
        this.$store.dispatch('getNaire', {
          n_id: this.$route.params.id
        }).then((response) => {
          if (response.data.err === 0) {
            this.$store.commit('REQUEST_QUESTION_LIST', {
              naire: response.data.data
            })
            // 通过 JSON 序列化将数组不再为引用，避免出现在 store 外修改 state 的内容
            this.naire = response.data.data
            this.spinShow = false
          } else {
            this.$Message.error(response.data.data)
            this.$router.push('/404')
          }
        })
          .catch((error) => {
            console.log(error)
            this.$Message.error('获取问卷，请重试')
          })
      },
      goBack () {
        this.$router.push('/platform')
      },
      validateNaire () {
        let _flag = true
        let _addtion = false
        let _isfinished = true
        this.naire.topic.forEach((item, index) => {
          if (item.isRequired) {
            if (item.type === '文本') {
              if (!item.selectContent.trim().length > 0) {
                _flag = false
              }
            }
            if (item.type === '单选') {
              const _isAddtion = item.options.some((option, index) => {
                return option.isAddition && option.o_id === item.selectContent
              })
              // 有附加理由的情况
              if (_isAddtion && !item.additional.trim().length > 0) {
                _addtion = true
              }
              if (!item.selectContent.trim().length > 0) {
                _flag = false
              }
            }
            if (item.type === '多选') {
              if (!item.selectMultipleContent.length > 0) {
                _flag = false
              }
            }
          }
        })
        if (!_flag) {
          this.error(true, '您还有必填项未填，请检查后提交！', '')
          _isfinished = false
        }
        if (_addtion) {
          this.error(true, '请填写附加理由', '')
          _isfinished = false
        }
        return _isfinished
      },
      submitNaire () {
        if (!this.validateNaire()) {
          return
        }
        const nId = this.naire.n_id
        const result = []
        this.naire.topic.forEach((question, index) => {
          if (question.type === '单选') {
            const curQues = {
              n_id: nId,
              q_id: question.q_id,
              o_id: question.selectContent,
              o_addition: question.additional.trim()
            }
            result.push(curQues)
          } else if (question.type === '多选') {
            const curQues = {
              n_id: nId,
              q_id: question.q_id,
              o_id: question.selectMultipleContent,
              o_addition: question.additional.trim()
            }
            result.push(curQues)
          } else {
            const curQues = {
              n_id: nId,
              q_id: question.q_id,
              o_id: '',
              o_addition: question.selectContent.trim()
            }
            result.push(curQues)
          }
        })

        this.$http.post('/api/naire/submit', {
          result: result
        })
          .then((response) => {
            console.log(response.data)
            // 影响行数大于0
            if (response.data.err === 0) {
              this.$Message.success(response.data.data)
              this.$router.push('/complete')
            } else {
              this.$Message.error(response.data.data)
            }
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('修改失败，请重试')
          })
      }
    },
    computed: {
      isAdmin () {
        return this.$store.getters.isAdmin
      }
    },
    created () {
      this.fetchData()
    },
    components: {
      questionList
    }
  }
</script>

<style scoped>

  .view-layout {
    background-color: rgb(237, 240, 248);
    width: 100%;
    padding: 20px 0;
  }

  .view-layout .main {
    width: 800px;
    height: auto;
    margin: 0 auto;
    padding-bottom: 30px;
    background-color: #fff;
    -webkit-box-shadow: 0 2px 5px 1px rgba(124, 124, 124, .2);
    box-shadow: 0 2px 5px 1px rgba(124, 124, 124, .2);
  }

  .view-layout .header {
    padding: 30px 20px;
    height: auto;
    min-height: 33px;
    border-bottom: 2px dotted #eee;
  }

  .view-layout .header h1 {
    width: 500px;
    margin: 0 auto;
    text-align: center;
  }

  .view-layout .content {
    padding: 20px;
  }

  .view-layout .intro {
    font-size: 14px;
    text-indent: 2em;
  }

  .view-layout .footer {
    margin-top: 10px;
    padding-top: 10px;
    text-align: center;
    border-top: 1px dotted #eee;
  }

  .code-row-bg button {
    margin: 0 10px;
  }
</style>
