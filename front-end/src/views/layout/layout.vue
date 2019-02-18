<template>
  <div class="layout">
    <Row type="flex">
      <i-col :span="5" class="layout-menu-left">
        <Menu :active-name="defaultActive" theme="dark" width="auto">
          <div class="layout-logo-left">微型问卷系统</div>
          <router-link v-for="(item, index) in navList" :to="item.to" :key="index">
            <Menu-item :name="item.name">
              <Icon :type="item.icon"></Icon>
              <span class="layout-text">{{ item.text }}</span>
            </Menu-item>
          </router-link>
        </Menu>
      </i-col>
      <i-col :span="19" class="layout-content-right">
        <div class="layout-header">
          <div class="header-nav" v-if="this.$route.matched[1].path === '/platform/statis'">
            <Menu mode="horizontal" theme="light" :active-name="subActive" @on-select="selectMenu">
              <Menu-item name="结果统计">
                <Icon type="pie-graph"></Icon>
                结果统计
              </Menu-item>
              <Menu-item name="样本数据">
                <Icon type="document-text"></Icon>
                样本数据
              </Menu-item>
              <Menu-item name="交叉分析">
                <Icon type="shuffle"></Icon>
                交叉分析
              </Menu-item>
            </Menu>
          </div>
          <a @click="logout" class="header-logout">注销 {{ userName }}</a>
        </div>
        <div class="layout-content">
          <div class="layout-content-main">
            <router-view></router-view>
          </div>
        </div>
        <div class="layout-copy">
          2009-2016 &copy; Timuwork
        </div>
      </i-col>
    </Row>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        subActive: this.$route.name,
        navList: [
          {
            to: '/platform/list',
            name: '/platform/list',
            text: '我的问卷',
            icon: 'ios-paper'
          },
          {
            to: '/platform/edit',
            name: '/platform/edit',
            text: '新建问卷',
            icon: 'android-create'
          },
          {
            to: '/platform/user',
            name: '/platform/user',
            text: '用户管理',
            icon: 'person'
          },
          {
            to: '/platform/admin',
            name: '/platform/admin',
            text: '管理员设置',
            icon: 'ios-analytics'
          }
        ]
      }
    },
    computed: {
      defaultActive () {
//        console.log(this.$route.matched[1])
        if (this.$route.matched.length > 1) {
          return this.$route.matched[1].path
        } else {
          return this.$route.path
        }
      },
      userName () {
        return this.$store.getters.getUser
      }
    },
    methods: {
      logout () {
//        console.log('logout')
        this.$store.dispatch('logout')
        this.$Message.warning('已登出!')
        this.$router.push('/login')
      },
      selectMenu (name) {
        this.subActive = name
        this.$router.push({
          name: name,
          params: {
            id: this.$route.params.id
          }
        })
      }
    }
  }
</script>

<style>

  .layout {
    background: #f5f7f9;
    position: relative;
    overflow: hidden;
  }

  .layout-content {
    margin: 15px;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
    min-height: 500px;
  }

  .layout-content-main {
    padding: 10px;
    min-height: 500px;
  }

  .layout-content-right {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: auto;
    box-sizing: border-box;
    overflow-y: scroll;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }

  .layout-copy {
    text-align: center;
    padding: 10px 0 20px;
    color: #9ea7b4;
  }

  .layout-menu-left {
    display: block;
    position: absolute;c
    width: 250px;
    left: 0;
    top: 0;
    bottom: 0;
    background: #464c5b;
  }

  .layout-header {
    height: 60px;
    background: #fff;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
    overflow: hidden;
  }

  .header-nav {
    float: left;
  }

  .layout-logo-left {
    width: 90%;
    min-height: 30px;
    /*background: #5b6270;*/
    text-align: center;
    font-size: 24px;
    color: #fff;
    line-height: 30px;
    border-radius: 3px;
    margin: 15px auto;
  }

  .layout-content-main {
    padding: 20px;
    background: #fff;
  }

  .layout-ceiling-main a {
    color: #9ba7b5;
  }

  .header-logout {
    float: right;
    padding-right: 30px;
    line-height: 60px;
    font-size: 14px;
  }
</style>
