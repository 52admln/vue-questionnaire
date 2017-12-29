<template>
    <div class="naire-list">
        <Row class="naire-btn">
            <Col span="24">
            <Button type="primary" @click="newNaire">新建问卷</Button>
            </Col>
        </Row>
        <Spin v-if="loading">
            <Icon type="load-c" size=18 class="demo-spin-icon-load"></Icon>
            <div>数据加载中...</div>
        </Spin>
        <Table border :context="self" :columns="columns7" :data="naireList" v-if="!loading"></Table>
        <!-- 复制地址 -->
        <Modal v-model="showURL" title="复制地址">
            <Row>
                <Col span="18">
                <Input v-model="url" ref="copyURL" id="url" :autofocus="true" :readonly="true"></Input>
                </Col>
                <Col span="4" offset="1">
                <Button class="copyboard" data-clipboard-target="#url" @click="handleCopy">复制</Button>
                </Col>
            </Row>
            <Alert style="margin-top: 20px">如无法使用上方复制按钮，请选中内容后，使用 Ctrl + C 复制。也可扫描下方二维码或右键保存二维码进行访问。</Alert>
            <div class="qrcode-wrapper">
                <div id="qrcode" ref="qrcode"></div>
            </div>
        </Modal>
        <!-- 修改截止时间 -->
        <Modal v-model="updateTime" title="修改截止时间" @on-ok="submitChangeTime">
            <Date-picker type="datetime" placeholder="截止日期"
                         v-model="deadline"
                         :editable="false" placement="bottom"
                         :options="dateOption">
            </Date-picker>
        </Modal>
        <!-- 查看回收情况 -->
        <Modal
                v-model="submitStatisModel"
                title="查看回收情况"
                :styles="{top: '40px'}"
                width="800">
            <Form :model="searchForm" inline>
                <Form-item prop="user" style="width: 150px;">
                    <Select v-model="searchForm.status" placeholder="请选择状态">
                        <Option value="-1">全部</Option>
                        <Option value="1">已完成</Option>
                        <Option value="0">未完成</Option>
                    </Select>
                </Form-item>
                <Form-item prop="password" style="width: 250px;">
                    <Select v-model="searchForm.u_class" placeholder="请选择班级">
                        <Option value="all">全部</Option>
                        <Option v-if="classList.length > 0" v-for="(item, index) in classList" :value="item.u_class"
                                :key="index">
                            {{ item.u_class }}
                        </Option>
                    </Select>
                </Form-item>
                <Form-item>
                    <Button type="primary" @click="handleSubmit">检索</Button>
                </Form-item>
            </Form>
            <Spin v-if="submitStatisLoading">
                <Icon type="load-c" size=18 class="demo-spin-icon-load"></Icon>
                <div>数据加载中...</div>
            </Spin>
            <Table border :context="self" :columns="submitStatisColumns" :data="submitStatisData"
                   v-if="!submitStatisLoading"></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;" v-if="!submitStatisLoading">
                    <Page :total="total" :current="currentPage" :page-size="pageSize" @on-change="changePage"></Page>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script>
  import QRCode from 'qrcodejs2'
  import { formatDate } from '../../common/js/utils'
  import Clipboard from 'clipboard'

  const OK = 0 // OK
  export default {
    data () {
      return {
        self: this,
        loading: true,
        url: '',
        showURL: false,
        searchForm: {
          u_class: '',
          status: ''
        },
        updateTimeId: 0,
        updateTime: false,
        deadline: '',
        dateOption: {
          disabledDate (date) {
            return date && date.valueOf() < Date.now() - 86400000
          }
        },
        classList: [],
        sexList: [
          {
            value: '0',
            label: '男'
          },
          {
            value: '1',
            label: '女'
          }
        ],
        submitStatisModel: false,
        submitStatisLoading: true,
        submitStatisData: [],
        submitStatisColumns: [
          {
            title: '用户ID',
            key: 'u_id',
            ellipsis: true
          },
          {
            title: '学号',
            key: 'u_number',
            ellipsis: true
          },
          {
            title: '姓名',
            key: 'u_name',
            ellipsis: true
          },
          {
            title: '性别',
            key: 'u_sex',
            ellipsis: true,
            render (row, column, index) {
              const sex = row.u_sex === '1' ? '女' : '男'
              return `${sex}`
            }
          },
          {
            title: '班级',
            key: 'u_class',
            ellipsis: true,
            render (row, column, index) {
              return row.u_class
            }
          },
          {
            title: '状态',
            key: 'is_finished',
            width: '100',
            render (row, column, index) {
              const status = row.is_finished === '1' ? '已完成' : '未完成'
              const color = row.is_finished === '1' ? 'green' : 'red'
              return `<Tag type="border" color="${color}">${status}</Tag>`.trim()
            }
          }
        ],
        currentId: 0,
        currentPage: 1,
        total: 5,
        pageSize: 10,
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
              const deadline = row.n_deadline // 获取截止时间
              const curtime = new Date().getTime() // 获取当前时间
              const status = curtime > deadline ? '已截止' : row.n_status === '0' ? '未发布' : '已发布'
              const color = (row.n_status === '0' || curtime > deadline) ? 'red' : 'green'
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
                              <Dropdown-item divided><span  @click="changeTime(${row.n_deadline}, ${row.n_id})">编辑截止时间</span></Dropdown-item>
                              <Dropdown-item ><span @click="changeStatus(${row.n_id},${row.n_status})">${_publishText}</span></Dropdown-item>
                          </Dropdown-menu>
                      </Dropdown>
                     `.trim()
            }
          }
        ],
        naireList: [],
        qrcode: null
      }
    },
    methods: {
      // 删除问卷
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
      // 统计
      statis (nid) {
        this.$router.push('/platform/statis/result/' + nid)
      },
      // 新建问卷
      newNaire () {
        this.$router.push('/platform/edit')
      },
      // 获取问卷列表数据
      getData () {
        this.$http.get('/api/naire')
          .then((response) => {
            this.naireList = response.data.data
            this.loading = false
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('网络错误，请重试')
          })
      },
      // 问卷预览
      preview (nid) {
        window.open(window.location.origin + '/#/view/' + nid)
      },
      // 复制问卷地址
      getURL (nid) {
        // 复制地址
        this.showURL = true
        this.url = window.location.origin + '/#/view/' + nid
        this.$nextTick(() => {
          if (this.qrcode === null) {
            this.qrcode = new QRCode(this.$refs.qrcode, { // eslint-disable-line no-new
              text: window.location.origin + '/#/view/' + nid,
              width: 250,
              height: 250,
              colorDark: '#000000',
              colorLight: '#ffffff',
              correctLevel: QRCode.CorrectLevel.H
            })
          } else {
            this.qrcode.makeCode(window.location.origin + '/#/view/' + nid)
          }
        })
      },
      // 问卷地址快速复制
      handleCopy () {
        let clipboard = new Clipboard('.copyboard')

        clipboard.on('success', (e) => {
          this.$Message.success('复制成功！')
          e.clearSelection()
        })

        clipboard.on('error', (e) => {
          this.$Message.error('复制失败！')
        })
      },
      // 分页
      changePage (curPage) {
        // 从服务端获取数据
        console.log(curPage)
        this.currentPage = curPage
        this._fetchStatisData()
      },
      submitStatis (nid) {
        // 查看统计情况
        this.submitStatisModel = true
        this.submitStatisLoading = true
        this.getStatisData(nid)
      },
      // 修改问卷状态
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
      },
      // 查看回收情况 数据获取
      getStatisData (id) {
        this.currentId = id
        this._fetchStatisData()
        this._getClass()
      },
      // 修改截止时间
      changeTime (time, id) {
        this.updateTime = true
        this.deadline = new Date(time).getTime()
        this.updateTimeId = id
      },
      // 提交截止时间修改
      submitChangeTime () {
        this.$http.post('/api/naire/changeTime', {
          n_id: this.updateTimeId,
          n_deadline: new Date(this.deadline).getTime()
        })
          .then((response) => {
            if (response.data.err === OK && response.data.data > 0) {
              this.$Message.success('问卷截止时间更改成功')
              this.getData()
            }
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('更改问卷截止时间失败')
          })
      },
      handleSubmit () {
        console.log(this.searchForm)
        this._fetchStatisData()
      },
      // 查看回收情况 数据获取
      _fetchStatisData () {
        this.submitStatisLoading = true
        // 从服务端获取数据
        // 往后台传2各参数，每页显示条数和当前页码
        this.$http.get('/api/naire/submitStatis', {
          params: {
            n_id: this.currentId,
            current: this.currentPage,
            page_size: this.pageSize,
            status: this.searchForm.status,
            u_class: this.searchForm.u_class
          }
        })
          .then((response) => {
            console.log(response.data)
            this.submitStatisData = response.data.data
            this.total = response.data.total
            this.submitStatisLoading = false
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('网络错误，请重试')
          })
      },
      _getClass () {
        this.$http.get('/api/user/getClass')
          .then((response) => {
            console.log(response.data)
            if (response.data.err === OK) {
              this.classList = response.data.data
            } else {
              this.$Message.error('获取班级列表失败，请重试')
            }
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

    .qrcode-wrapper {
        padding: 20px;
        background-color: #fff;
    }

    #qrcode {
        margin: 0 auto;
        width: 250px;
        height: 250px;
    }
</style>
