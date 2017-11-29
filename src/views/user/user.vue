<template>
    <div class="user">
        <Row type="flex" justify="start" style="padding-bottom: 10px;">
            <Col span="2" style="width: 100px">
            <Button type="primary" @click="handleAdd">新增用户</Button>
            </Col>
            <Col span="2">
            <Upload
                    action="/api/user/upload"
                    accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    name="userfile"
                    :show-upload-list="false"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    :on-success="handleSuccess"
                    :on-error="handleError"
            >
                <Button type="ghost" icon="ios-cloud-upload-outline">批量导入</Button>
            </Upload>
            </Col>

        </Row>
        <Row>
            <Col span="24">
            <Spin fix v-if="loading">
                <Icon type="load-c" size=18      class="demo-spin-icon-load"></Icon>
                <div>数据加载中...</div>
            </Spin>
            <Table border :context="self" :columns="tableColumns" :data="userData" v-if="!loading"></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;" v-if="!loading">
                    <Page :total="total" :current="currentPage" :page-size="pageSize" @on-change="changePage"></Page>
                </div>
            </div>
            </Col>
        </Row>

        <!-- 新增用户表单 -->
        <Modal v-model="addUserModel"
               title="新增用户"
               :mask-closable="false">
            <!-- form表单-->
            <Form ref="addUser" :model="addUserForm"
                  :rules="userRule"
                  :label-width="80"
                  class="form">
                <FormItem label="姓名" prop="name">
                    <Input v-model="addUserForm.u_name"
                           placeholder=""></Input>
                </FormItem>
                <FormItem label="专业名称" prop="major">
                    <Input v-model="addUserForm.u_major"
                           placeholder=""></Input>
                </FormItem>
                <FormItem label="班级名称" prop="class">
                    <Input v-model="addUserForm.u_class"
                           placeholder=""></Input>
                </FormItem>
                <FormItem label="学号" prop="number">
                    <Input v-model="addUserForm.u_number"
                           placeholder=""></Input>
                </FormItem>
                <FormItem label="出生日期" prop="birthday">
                    <Input v-model="addUserForm.u_birthday"
                           placeholder="格式：20171001"></Input>
                </FormItem>
                <FormItem label="民族" prop="nation">
                    <Input v-model="addUserForm.u_nation"
                           placeholder=""></Input>
                </FormItem>
                <FormItem label="身份证号" prop="identity">
                    <Input v-model="addUserForm.u_identity"
                           placeholder="如最后为字母，以大写结尾"></Input>
                </FormItem>
                <FormItem label="性别" prop="sex">
                    <RadioGroup v-model="addUserForm.u_sex">
                        <Radio v-for="item in sexList"
                               :key="item.value"
                               :label="item.value">{{item.label}}
                        </Radio>
                    </RadioGroup>
                </FormItem>
            </Form>
            <div slot="footer">
                <Button type="primary" :loading="addFormLoading" @click="submitUser('addUser')">提交</Button>
                <Button @click="addUserModel = false">关闭</Button>
            </div>
        </Modal>

        <!-- 编辑用户表单 -->
        <Modal v-model="updateUserModel"
               title="新增用户"
               :mask-closable="false">
            <!-- form表单-->
            <Form ref="updateUser" :model="updateUserForm"
                  :rules="userRule"
                  :label-width="80"
                  class="form">
                <FormItem label="姓名" prop="name">
                    <Input v-model="updateUserForm.u_name"
                           placeholder=""></Input>
                </FormItem>
                <FormItem label="专业名称" prop="major">
                    <Input v-model="updateUserForm.u_major"
                           placeholder=""></Input>
                </FormItem>
                <FormItem label="班级名称" prop="class">
                    <Input v-model="updateUserForm.u_class"
                           placeholder=""></Input>
                </FormItem>
                <FormItem label="学号" prop="number">
                    <Input v-model="updateUserForm.u_number"
                           placeholder=""></Input>
                </FormItem>
                <FormItem label="出生日期" prop="birthday">
                    <Input v-model="updateUserForm.u_birthday"
                           placeholder="格式：20171001"></Input>
                </FormItem>
                <FormItem label="民族" prop="nation">
                    <Input v-model="updateUserForm.u_nation"
                           placeholder=""></Input>
                </FormItem>
                <FormItem label="身份证号" prop="identity">
                    <Input v-model="updateUserForm.u_identity"
                           placeholder="如最后为字母，以大写结尾"></Input>
                </FormItem>
                <FormItem label="性别" prop="sex">
                    <RadioGroup v-model="updateUserForm.u_sex">
                        <Radio v-for="item in sexList"
                               :key="item.value"
                               :label="item.value">{{item.label}}
                        </Radio>
                    </RadioGroup>
                </FormItem>
            </Form>
            <div slot="footer">
                <Button type="primary" :loading="updateFormLoading" @click="updateUser('updateUser')">提交</Button>
                <Button @click="updateUserModel = false">关闭</Button>
            </div>
        </Modal>
    </div>
</template>

<script>
  const OK = 0 // OK
  export default {
    data () {
      return {
        self: this,
        loading: true,
        currentPage: 1,
        total: 5,
        pageSize: 10,
        tableColumns: [
          {
            type: 'selection',
            width: 60,
            align: 'center'
          },
          {
            title: 'ID',
            key: 'u_id'
          },
          {
            title: '学号',
            key: 'u_number'
          },
          {
            title: '姓名',
            key: 'u_name'
          },
          {
            title: '性别',
            key: 'u_sex',
            render (row, column, index) {
              const sex = row.u_sex === '1' ? '女' : '男'
              return `${sex}`
            }
          },
          {
            title: '班级',
            key: 'u_class'
          },
          {
            title: '身份证号',
            key: 'u_identity'
          },
          {
            title: '出生日期',
            key: 'u_birthday'
          },
          {
            title: '民族',
            key: 'u_nation'
          },
          {
            title: '操作',
            key: 'action',
            width: 130,
            align: 'center',
            render (row, column, index) {
              return `<i-button type="primary" size="small" @click="update(${index})">修改</i-button>
                    <Poptip
        placement="left"
        confirm
        title="您确认删除这条内容吗？"
        width="200"
        @on-ok="remove(${index})">
                      <i-button type="error" size="small">删除</i-button> </Poptip>`

                .trim()
            }
          }
        ],
        userData: [],
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
        addFormLoading: false,
        userRule: {
          u_name: [
            {required: true, message: '请输入姓名', trigger: 'blur'}
          ],
          u_major: [
            {required: true, message: '请输入专业名称', trigger: 'blur'}
          ],
          u_class: [
            {required: true, message: '请输入班级', trigger: 'blur'}
          ],
          u_number: [
            {required: true, message: '请输入学号', trigger: 'blur'}
          ],
          u_birthday: [
            {required: true, message: '请输入出生日期', trigger: 'blur'}
          ],
          u_nation: [
            {required: true, message: '请输入民族', trigger: 'blur'}
          ],
          u_sex: [
            {required: true, message: '请选择性别', trigger: 'change'}
          ],
          u_identity: [
            {required: true, message: '请输入身份证号', trigger: 'blur'}
          ]
        },
        addUserForm: {
          u_name: '',
          u_major: '',
          u_class: '',
          u_number: '',
          u_birthday: '',
          u_nation: '',
          u_identity: '',
          u_sex: ''
        },
        addUserModel: false,
        updateFormLoading: false,
        updateUserModel: false,
        updateUserForm: {
          u_id: '',
          u_name: '',
          u_major: '',
          u_class: '',
          u_number: '',
          u_birthday: '',
          u_nation: '',
          u_identity: '',
          u_sex: ''
        }
      }
    },
    methods: {
      // 编辑用户
      update (index) {
        this.$http.get('/api/user', {
          params: {
            u_id: this.userData[index].u_id
          }
        })
          .then((response) => {
            console.log(response.data.data)
            if (response.data.err === OK) {
              this.updateUserForm = response.data.data[0]
              this.updateUserModel = true
            }
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('获取用户数据失败')
          })
      },
      // 删除用户
      remove (index) {
        console.log(index)
        this.$http.get('/api/user/del', {
          params: {
            u_id: this.userData[index].u_id
          }
        })
          .then((response) => {
            console.log(response.data.data)
            if (response.data.data > 0 && response.data.err === OK) {
              this.$Message.success('删除成功')
              this.userData.splice(index, 1)
            }
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('删除失败')
          })
      },
      // 分页
      changePage (curPage) {
        // 从服务端获取数据
        console.log(curPage)
        this.loading = true
        this.currentPage = curPage
        // 往后台传2各参数，每页显示条数和当前页码
        this.$http.get('/api/user', {
          params: {
            current: curPage,
            page_size: this.pageSize
          }
        })
          .then((response) => {
            this.userData = response.data.data
            this.total = response.data.total
            this.loading = false
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('网络错误，请重试')
          })
      },
      // 拉取用户数据
      getData () {
        this.$http.get('/api/user', {
          params: {
            current: this.currentPage,
            page_size: this.pageSize
          }
        })
          .then((response) => {
            this.userData = response.data.data
            this.total = response.data.total
            this.loading = false
          })
          .catch((error) => {
            console.log(error)
            this.$Message.error('网络错误，请重试')
          })
      },
      // 文件格式校验
      handleFormatError (file) {
        this.$Notice.warning({
          title: '文件格式不正确',
          desc: '文件 ' + file.name + ' 格式不正确，请上传 xls 或 xlsx 格式的文件。'
        })
      },
      // 文件大小检验
      handleMaxSize (file) {
        this.$Notice.warning({
          title: '超出文件大小限制',
          desc: '文件 ' + file.name + ' 太大，不能超过 2M。'
        })
      },
      // 文件上传成功的回调
      handleSuccess (response, file, fileList) {
        console.log(response)
        if (response.err === 0) {
          this.$Message.success(response.msg)
          this.getData()
        } else {
          this.$Message.error('上传出错')
        }
      },
      // 文件长传失败的回调
      handleError (error, file, fileList) {
        console.log(error)
        this.$Message.error('上传出错')
      },
      // 显示新增用户弹窗
      handleAdd () {
        this.addUserModel = true
      },
      // 编辑用户提交
      updateUser (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            this.$http.post('/api/user/updateUser', Object.assign({}, this.updateUserForm))
              .then((response) => {
                console.log(response.data.data)
                if (response.data.err === OK) {
                  this.$Message.success(response.data.data)
                  this.updateUserModel = false
                  this.$refs[name].resetFields()
                  this.getData()
                } else {
                  this.$Message.error(response.data.data)
                }
              })
              .catch((error) => {
                console.log(error)
                this.$Message.error('修改失败')
              })
          } else {
            this.$Message.error('表单校验失败！')
          }
        })
      },
      // 提交新增用户
      submitUser (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            this.$http.post('/api/user/addUser', Object.assign({}, this.addUserForm))
              .then((response) => {
                if (response.data.err === OK) {
                  this.$Message.success(response.data.data)
                  this.addUserModel = false
                  this.$refs[name].resetFields()
                  this.getData()
                } else {
                  this.$Message.error(response.data.data)
                }
              })
              .catch((error) => {
                console.log(error)
                this.$Message.error('新增失败')
              })
          } else {
            this.$Message.error('表单校验失败！')
          }
        })
      }
    },
    created () {
      this.getData()
    }
  }
</script>

<style>

</style>
