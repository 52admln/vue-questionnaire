<template>
  <div class="naire-list">
    <Row class="naire-btn">
      <Col span="24"><Button type="primary" @click="newNaire">新建问卷</Button> </Col>
    </Row>
    <Table border :context="self" :columns="columns7" :data="naireList"></Table>
  </div>
</template>

<script>
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
            key: 'n_title'
          },
          {
            title: '问卷介绍',
            key: 'n_intro',
            ellipsis: true
          },
          {
            title: '结束时间',
            key: 'n_deadline'
          },
          {
            title: '创建时间',
            key: 'n_creattime'
          },
          {
            title: '状态',
            key: 'n_status',
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
            width: 170,
            align: 'center',
            render (row, column, index) {
              return `<i-button size="small">查看</i-button> <i-button type="primary" size="small">编辑</i-button> <i-button type="error" size="small" @click="remove(${index})">删除</i-button>`
            }
          }
        ],
        naireList: []
      }
    },
    methods: {
      remove (index) {
        this.naireList.splice(index, 1)
      },
      newNaire () {
        this.$router.push('/platform/new')
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
