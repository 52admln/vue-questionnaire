<template>
  <div class="view-layout">
    <div class="main">
      <div class="header">
        <h1>{{naire.title}}</h1>
      </div>
      <div class="content">
        <div class="intro">
          <p>问卷介绍：{{naire.intro}}</p>
          <p>截止日期：{{naire.deadline | formatDate}}</p>
        </div>
        <questionList :question-list="this.naire.topic">
          <Row type="flex" justify="center" align="middle" class="code-row-bg">
            <Button type="primary" v-if="isAdmin" @click="window.history.go(-1)">返回</Button>
            <Button type="primary" :disabled="isAdmin">提交问卷</Button>
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

  export default {
    data () {
      return {
        naire: {
          topic: []
        }
      }
    },
    filters: {
      formatDate (value) {
        const date = new Date(value)
        const Y = date.getFullYear() + '-'
        const M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-'
        const D = date.getDate() + ' '
        return Y + M + D
      }
    },
    watch: {
      // 如果路由有变化，会再次执行该方法
      '$route': 'fetchData'
    },
    methods: {
      fetchData () {
        this.$store.dispatch('getNaire', {
          n_id: this.$route.params.id
        }).then((response) => {
          if (response.data.err === 0) {
            this.$store.commit('REQUEST_QUESTION_LIST', {
              naire: response.data.data
            })
            // 通过 JSON 序列化将数组不再为引用，避免出现在 store 外修改 state 的内容
            this.naire = response.data.data
          } else {
            this.$Message.error(response.data.data)
            this.$router.push('/404')
          }
        })
          .catch((error) => {
            console.log(error)
            this.$Message.error('获取问卷，请重试')
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

<style>
  .view-layout {
    width: 100%;
    height: 100%;
    padding: 20px 0;
    background-color: rgb(237, 240, 248);
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
    padding: 15px 20px;
    height: auto;
    min-height: 33px;
    border-bottom: 2px dotted #eee;
  }

  .view-layout .header h1 {
    text-align: center;
  }

  .view-layout .content {
    padding: 20px;
  }

  .view-layout .intro {
    font-size: 14px;
  }

  .view-layout .footer {
    margin-top: 10px;
    padding-top: 10px;
    text-align: center;
    border-top: 1px dotted #eee;
  }
</style>
