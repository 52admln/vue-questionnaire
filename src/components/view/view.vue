<template>
  <div class="view-layout">
    <div class="main">
      <div class="header">
        <h1>测试标题</h1>
      </div>
      <div class="content">
        <questionList :question-list="this.questionList">
          <Row type="flex" justify="center" align="middle" class="code-row-bg">
            <Button type="primary">提交问卷</Button>
          </Row>
        </questionList>
      </div>

    </div>
  </div>
</template>

<script>
  import questionList from '@/components/common/questionList/questionList'

  export default {
    data () {
      return {
        questionList: []
      }
    },
    created () {
      this.$store.dispatch('getQuestionList')
      // 通过 JSON 序列化将数组不再为引用，避免出现在 store 外修改 state 的内容
      let data = JSON.stringify(this.$store.getters.questionList)
      this.questionList = JSON.parse(data)
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
</style>
