<template>
  <div class="naire-list">
    <Row class="naire-btn">
      <Col span="24"><Button type="primary" @click="newNaire">新建问卷</Button> </Col>
    </Row>
    <Table border :context="self" :columns="columns7" :data="data6"></Table>
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
            key: 'name',
            render (row, column, index) {
              return `<Icon type="person"></Icon> <strong>${row.name}</strong>`
            }
          },
          {
            title: '结束时间',
            key: 'age'
          },
          {
            title: '状态',
            key: 'address'
          },
          {
            title: '创建时间',
            key: 'address'
          },
          {
            title: '操作',
            key: 'action',
            width: 170,
            align: 'center',
            render (row, column, index) {
              return `<i-button size="small" @click="show(${index})">查看</i-button> <i-button type="primary" size="small" @click="show(${index})">编辑</i-button> <i-button type="error" size="small" @click="remove(${index})">删除</i-button>`
            }
          }
        ],
        data6: [
          {
            name: '王小明',
            age: 18,
            address: '北京市朝阳区芍药居'
          },
          {
            name: '张小刚',
            age: 25,
            address: '北京市海淀区西二旗'
          },
          {
            name: '李小红',
            age: 30,
            address: '上海市浦东新区世纪大道'
          },
          {
            name: '周小伟',
            age: 26,
            address: '深圳市南山区深南大道'
          }
        ]
      }
    },
    methods: {
      show (index) {
        this.$Modal.info({
          title: '用户信息',
          content: `姓名：${this.data6[index].name}<br>年龄：${this.data6[index].age}<br>地址：${this.data6[index].address}`
        })
      },
      remove (index) {
        this.data6.splice(index, 1)
      },
      newNaire () {
        this.$router.push('/platform/new')
      }
    }
  }
</script>

<style>
  .naire-btn {
    padding-bottom: 10px;
  }
</style>
