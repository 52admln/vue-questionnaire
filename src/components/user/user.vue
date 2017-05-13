<template>
  <div class="user">
    <Row type="flex" justify="start" style="padding-bottom: 10px;">
      <Col span="2" style="width: 100px;">
      <Button type="primary">新增用户</Button>
      </Col>
      <Col span="2" style="width: 100px;">
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
      <Spin fix v-show="!loading">
        <Icon type="load-c" size=18   class="demo-spin-icon-load"></Icon>
        <div>数据加载中...</div>
      </Spin>
      <Table border :context="self" :columns="tableColumns" :data="userData" v-show="loading"></Table>
      <div style="margin: 10px;overflow: hidden">
        <div style="float: right;" v-show="loading">
          <Page :total="total" :current="currentPage" :page-size="pageSize" @on-change="changePage"></Page>
        </div>
      </div>
      </Col>
    </Row>
  </div>
</template>

<script>
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
            title: '用户 ID',
            key: 'user_id'
          },
          {
            title: '学号',
            key: 's_number'
          },
          {
            title: '姓名',
            key: 'name'
          },
          {
            title: '性别',
            key: 'sex'
          },
          {
            title: '班级',
            key: 'classname'
          },
          {
            title: '身份证号',
            key: 'identity'
          },
          {
            title: '出生日期',
            key: 'birthday'
          },
          {
            title: '民族',
            key: 'nation'
          },
          {
            title: '操作',
            key: 'action',
            width: 180,
            align: 'center'
          }
        ],
        userData: [
          {
            user_id: 1,
            s_number: 201500,
            name: 'wang',
            sex: '1',
            classname: 'class1',
            identity: 'wdadaefd23423523453',
            birthday: 'sdasdsadad',
            nation: 'sdasd'
          }
        ],
        sexList: [
          {
            value: '0',
            label: '男'
          },
          {
            value: '1',
            label: '女'
          }
        ]
      }
    },
    methods: {
      update () {
        console.log('update')
      },
      remove () {
        console.log('remove')
      },
      changePage (curPage) {
        // 这里直接更改了模拟的数据，真实使用场景应该从服务端获取数据
        // this.userData = this.mockTableData1();
        console.log(curPage)
        // 往后台传2各参数，每页显示条数和当前页码
//        this.$http.get('/api/user', {
//          params: {
//            current: curPage,
//            page_size: this.pageSize
//          }
//        })
//          .then((response) => {
//            this.userData = response.data.data;
//            this.total = response.data.total;
//          })
//          .catch((error) => {
//            console.log(error);
//            this.$Message.error('网络错误，请重试');
//          });
      },
      getData () {
//        this.$http.get('/api/user', {
//          params: {
//            current: this.currentPage,
//            page_size: this.pageSize
//          }
//        })
//          .then((response) => {
//            this.userData = response.data.data;
//            this.total = response.data.total;
//            this.loading = true;
//          })
//          .catch((error) => {
//            console.log(error);
//            this.$Message.error('网络错误，请重试');
//          });
      },
      handleFormatError (file) {
        this.$Notice.warning({
          title: '文件格式不正确',
          desc: '文件 ' + file.name + ' 格式不正确，请上传 xls 或 xlsx 格式的文件。'
        })
      },
      handleMaxSize (file) {
        this.$Notice.warning({
          title: '超出文件大小限制',
          desc: '文件 ' + file.name + ' 太大，不能超过 2M。'
        })
      },
      handleSuccess (response, file, fileList) {
        console.log(response)
        if (response.err === 0) {
          this.$Message.success(response.msg)
        } else {
          this.$Message.error('上传出错')
        }
      },
      handleError (error, file, fileList) {
        console.log(error)
        this.$Message.error('上传出错')
      }
    },
    created () {
      this.getData()
    }
  }
</script>

<style>

</style>
