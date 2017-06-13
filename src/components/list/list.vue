<template>
  <div class="naire-list">
    <Row class="naire-btn">
      <Col span="24">
      <Button type="primary" @click="newNaire">新建问卷</Button>
      </Col>
    </Row>
    <Table border :context="self" :columns="columns7" :data="naireList"></Table>
    <Modal v-model="showURL">
      <Input v-model="url" ref="copyURL" :autofocus="true" :readonly="true"></Input>
      <p>选中后，使用 Ctrl + C 复制。</p>
    </Modal>
  </div>
</template>

<script>
  import { formatDate } from '../../common/js/utils'
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
            width: '100',
            render (row, column, index) {
              const deadline = new Date(row.n_deadline).getTime() // 获取截止时间
              const curtime = new Date().getTime() // 获取当前时间
              const status = curtime > deadline ? '已截止' : row.n_status === '0' ? '未发布' : '已发布'
              const color = row.n_status === 0 ? 'red' : 'green'
              return `<Tag type="border" color="${color}">${status}</Tag>`.trim()
            }
          },
          {
            title: '操作',
            key: 'action',
            width: 210,
            align: 'center',
            render (row, column, index) {
              const _publishText = row.n_status === '1' ? '停止发布' : '发布问卷'
              return `<i-button type="warning" size="small" @click="statis(${row.n_id})">统计</i-button>
                      <Poptip
                            placement="left"
                            confirm
                            title="您确认删除这条内容（包含题目和选项）吗？"
                            width="200"
                            @on-ok="remove(${index})">
                     <i-button type="error" size="small">删除</i-button>
                     </Poptip>
                      <Dropdown>
                          <i-button type="primary" size="small">
                              更多操作
                              <Icon type="arrow-down-b"></Icon>
                          </i-button>
                          <Dropdown-menu slot="list">
                              <Dropdown-item><span @click="preview(${row.n_id})">预览问卷</span></Dropdown-item>
                              <Dropdown-item><span @click="getURL(${row.n_id})">复制地址</span></Dropdown-item>
                              <Dropdown-item><span @click="submitStatis(${row.n_id})">查看回收情况</span></Dropdown-item>
                              <Dropdown-item divided disabled>编辑问卷</Dropdown-item>
                              <Dropdown-item ><span @click="changeStatus(${row.n_id},${row.n_status})">${_publishText}</span></Dropdown-item>
                          </Dropdown-menu>
                      </Dropdown>
                     `.trim()
            }
          }
        ],
        naireList: [],
        url: '',
        showURL: false
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
            if (response.data.err === OK) {
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
      statis (nid) {
        this.$router.push('/platform/statis/result/' + nid)
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
      },
      submitStatis (nid) {
        // 查看统计情况
      },
      getURL (nid) {
        // 复制地址
        this.showURL = true
        this.url = window.location.origin + '/#/view/' + nid
      },
      changeStatus (nId, status) {
        console.log(nId, status)
        this.$http.get('/api/naire/changeStatus', {
          params: {
            n_id: nId
          }
        })
          .then((response) => {
            console.log(response.data.data)
            if (response.data.err === OK && response.data.data > 0) {
              this.$Message.success('问卷更改状态成功')
              this.getData()
            }
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('更改状态失败')
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
