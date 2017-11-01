<template>
  <div class="view-layout">
    <Spin size="large" fix v-if="spinShow"></Spin>
    <div class="main" v-if="isNotPublish && !isAdmin">
      <div class="header">
        <h1>问卷未发布！</h1>
      </div>
      <div class="content">
        <p>您所填写的问卷未发布，暂不能填写。</p>
      </div>
    </div>
    <div class="main" v-else-if="isExpired && !isAdmin">
      <div class="header">
        <h1>问卷已过期！</h1>
      </div>
      <div class="content">
        <p>您所填写的问卷已到截止日期，暂不能填写。</p>
      </div>
    </div>
    <div class="main" v-if="(!isExpired && !isNotPublish) || isAdmin">
      <div class="header">
        <h1>{{naire.title}}</h1>
      </div>
      <div class="content">
        <div class="intro">
          <p>{{naire.intro}}</p>
          <p>截止日期：{{naire.deadline | timeFormat}}</p>
        </div>
        <div class="user-info">
          <Alert v-show="!isLogin">在填写表单之前，请先填写用户信息。</Alert>
          <Form ref="userInfo" :model="userInfo" :rules="userInfoRule" inline>
            <Form-item prop="name">
              <Input v-model="userInfo.name" placeholder="请输入姓名"></Input>
            </Form-item>
            <Form-item prop="identity">
              <Input v-model="userInfo.identity" placeholder="请输入身份证号"></Input>
            </Form-item>
            <Form-item>
              <Button type="primary" @click="handleSubmit('userInfo')">点击确认</Button>
            </Form-item>
          </Form>
        </div>
        <questionList :question-list="this.naire.topic">
          <Row type="flex" justify="center" align="middle" class="code-row-bg">
            <Button type="success"
                    v-if="isAdmin"
                    @click="goBack">返回管理平台
            </Button>
            <Button type="primary"
                    @click="submitNaire" :disabled="finished">提交问卷
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
        finished: false,
        spinShow: true,
        isLogin: false,
        userId: 0,
        userInfo: {
          name: '',
          identity: ''
        },
        userInfoRule: {
          name: [
            {required: true, message: '请填写用户名', trigger: 'blur'}
          ],
          identity: [
            {required: true, message: '请填写身份证', trigger: 'blur'},
            {type: 'string', max: 18, min: 18, message: '身份证长度不正确', trigger: 'blur'}
          ]
        }
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
      handleSubmit (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
// 查找用户，返回用户id
            this.$http.post('/api/user/getId', {
              n_id: this.naire.n_id,
              name: this.userInfo.name,
              identity: this.userInfo.identity.toUpperCase()
            })
              .then((response) => {
                console.log(response.data.data)
                // 用户存在,服务器返回 u_id
                if (response.data.err === 0 && !response.data.data.isFinished) {
                  this.isLogin = true
                  console.log(response.data.data.u_id)
                  this.userId = response.data.data.u_id
                  this.$Notice.open({
                    title: '欢迎您 ' + response.data.data.name,
                    desc: '请继续完成问卷内容吧！',
                    duration: 5
                  })
                } else if (response.data.data.isFinished) {
                  this.$Notice.warning({
                    title: '已完成问卷',
                    desc: '您已完成该问卷，请勿重复提交！',
                    duration: 5
                  })
                  this.$router.push('/complete')
                } else {
                  this.$Notice.warning({
                    title: '用户不存在',
                    desc: '请确认姓名和身份证号后重试！',
                    duration: 5
                  })
                }
              })
              .catch((error) => {
                console.log(error)
                this.$Message.error('用户登录失败，请重试')
              })
          } else {
            this.$Message.error('请先填写用户信息!')
          }
        })
      },
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
        if (!this.isLogin) {
          this.error(true, '请先填写用户信息', '')
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
              u_id: this.userId,
              q_id: question.q_id,
              o_id: question.selectContent,
              o_addition: question.additional.trim()
            }
            result.push(curQues)
          } else if (question.type === '多选') {
            const curQues = {
              n_id: nId,
              u_id: this.userId,
              q_id: question.q_id,
              o_id: question.selectMultipleContent,
              o_addition: question.additional.trim()
            }
            result.push(curQues)
          } else {
            const curQues = {
              n_id: nId,
              u_id: this.userId,
              q_id: question.q_id,
              o_id: '',
              o_addition: question.selectContent.trim()
            }
            result.push(curQues)
          }
        })
        console.log(result)
        // 防止重复提交
        this.finished = true
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
              this.finished = false
            }
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('提交失败，请重试')
            this.finished = false
          })
      }
    },
    computed: {
      isAdmin () {
        return this.$store.getters.isAdmin
      },
      isExpired () {
        return Number(this.naire.deadline) < new Date().getTime()
      },
      isNotPublish () {
        return this.naire.status === '0'
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
    min-height: 100%;
    max-height: 100%;
    height: 100%;
    width: 100%;
    padding: 20px 0;
    overflow-y: scroll;
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

  .user-info {
    width: 100%;
    padding: 30px 30px 0 30px;
  }
</style>
