<template>
  <div class="layout">
    <Row type="flex">
      <i-col :span="5" class="layout-menu-left">
        <Menu :active-name="defaultActive" theme="dark" width="auto">
          <div class="layout-logo-left">微型问卷系统</div>
          <router-link to="/platform/list">
            <Menu-item name="/platform/list">
              <Icon type="ios-paper"></Icon>
              <span class="layout-text">我的问卷</span>
            </Menu-item>
          </router-link>
          <router-link to="/platform/edit">
            <Menu-item name="/platform/edit">
              <Icon type="android-create"></Icon>
              <span class="layout-text">新建问卷</span>
            </Menu-item>
          </router-link>
          <router-link to="/platform/user">
            <Menu-item name="/platform/user">
              <Icon type="person"></Icon>
              <span class="layout-text">用户管理</span>
            </Menu-item>
          </router-link>
        </Menu>
      </i-col>
      <i-col :span="19" class="layout-content-right">
        <div class="layout-header">
          <a @click="logout" class="header-logout">注销</a>
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
    computed: {
      defaultActive () {
        console.log(this.$route.matched[1])
        if (this.$route.matched.length > 1) {
          return this.$route.matched[1].path
        } else {
          return this.$route.path
        }
      }
    },
    methods: {
      logout () {
        console.log('logout')
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
    overflow: hidden;
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
    position: absolute;
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
  }

  .layout-logo-left {
    width: 90%;
    height: 30px;
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
