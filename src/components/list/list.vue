<template>
  <div class="naire-list">
    <Row class="naire-btn">
      <Col span="24">
      <Button type="primary" @click="newNaire">新建问卷</Button>
      </Col>
    </Row>
    <Table border :context="self" :columns="columns7" :data="naireList"></Table>
  </div>
</template>

<script>
  import { formatDate } from '../../utils'
  const OK = 0 // OK
  export default {
    data () {
      return {
        self: this,
        columns7: [
          {
            type: 'selection',
            width: 60,
            align: 'center'
          },
          {
            title: '问卷名称',
            key: 'n_title',
            ellipsis: true
          },
          {
            title: '问卷介绍',
            key: 'n_intro',
            ellipsis: true
          },
          {
            title: '结束时间',
            key: 'n_deadline',
            ellipsis: true,
            render (row, column, index) {
              return formatDate(row.n_deadline)
            }
          },
          {
            title: '创建时间',
            key: 'n_creattime',
            ellipsis: true,
            render (row, column, index) {
              return formatDate(row.n_creattime)
            }
          },
          {
            title: '状态',
            key: 'n_status',
            width: '90',
            render (row, column, index) {
              const deadline = new Date(row.n_deadline).getTime() // 获取截止时间
              const curtime = new Date().getTime() // 获取当前时间
              const status = curtime > deadline ? '已截止' : row.n_status === '0' ? '未发布' : '已发布'
              return `${status}`
            }
          },
          {
            title: '操作',
            key: 'action',
            width: 280,
            align: 'center',
            render (row, column, index) {
              return `<i-button size="small" @click="preview(${row.n_id})">预览</i-button> <i-button type="success" size="small">获取地址</i-button> <i-button type="warning" size="small">统计</i-button> <i-button type="primary" size="small">编辑</i-button>
           <Poptip
                  placement="left"
                  confirm
                  title="您确认删除这条内容（包含题目和选项）吗？"
                  width="200"
                  @on-ok="remove(${index})">
           <i-button type="error" size="small">删除</i-button>
           </Poptip>`
            }
          }
        ],
        naireList: []
      }
    },
    methods: {
      remove (index) {
        console.log(index)
        this.$http.get('/api/naire/del', {
          params: {
            n_id: this.naireList[index].n_id
          }
        })
          .then((response) => {
            console.log(response.data.data)
            if (response.data.data > 0 && response.data.err === OK) {
              this.$Message.success('删除成功')
              this.naireList.splice(index, 1)
              this.getData()
            }
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('删除失败')
          })
      },
      newNaire () {
        this.$router.push('/platform/edit')
      },
      getData () {
        this.$http.get('/api/naire')
          .then((response) => {
            this.naireList = response.data.data
            this.loading = true
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('网络错误，请重试')
          })
      },
      preview (index) {
        this.$router.push('/view/' + index)
      }
    },
    created () {
      this.getData()
    }
  }
</script>

<style>
  .naire-btn {
    padding-bottom: 10px;
  }
</style>
