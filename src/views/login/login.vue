<template>
    <div class="login-wrapper">
        <div class="login-form">
            <h3 class="title">微型问卷系统后台管理</h3>
            <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" label-position="top" class="form-area" @submit.prevent @keyup.enter="handleSubmit('formValidate')">
                <Form-item label="用户名" prop="username">
                    <Input v-model="formValidate.username"></Input>
                </Form-item>
                <Form-item label="密码" prop="password">
                    <Input type="password" v-model="formValidate.password"></Input>
                </Form-item>
                <Form-item>
                    <Button type="primary" html-type="submit"  @click="handleSubmit('formValidate')" long>立即登录
                    </Button>
                </Form-item>
            </Form>
        </div>
        <div class="login-logo"></div>
        <div class="login-github">
            <a class="icon-github" href="https://github.com/52admln/vue-questionnaire" target="_blank">
                    <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB0PSIxNTA0NjgwOTI2NTgxIiBjbGFzcz0iaWNvbiIgc3R5bGU9IiIgdmlld0JveD0iMCAwIDEwMzIgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHAtaWQ9IjQyNzAiIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMjAxLjU2MjUiIGhlaWdodD0iMjAwIj48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwvc3R5bGU+PC9kZWZzPjxwYXRoIGQ9Ik0wIDUxNS44NzJDMCA3NDEuMTIgMTQ0LjM1MiA5MzIuNjQgMzQ1LjU4NCAxMDAyLjk3NmMyNy4xMiA2Ljg5NiAyMi44OTYtMTIuNTQ0IDIyLjg5Ni0yNS42MTZsMC04OS40MjRjLTE1Ni40NjQgMTguMzA0LTE2Mi43MzYtODUuMjgtMTczLjI5Ni0xMDIuNjA4LTIxLjMxMi0zNi4yNzItNzEuMzkyLTQ1LjQ4OC01Ni40MzItNjIuNzUyIDM1LjY5Ni0xOC40MzIgNzEuOTY4IDQuNjA4IDExNCA2Ni43ODQgMzAuNDY0IDQ1LjA3MiA4OS42NjQgMzcuNTA0IDExOS45MzYgMjkuOTM2IDYuNjI0LTI3LjA1NiAyMC43MDQtNTEuMiAzOS45NTItNzAuMDgtMTYxLjg4OC0yOC44MTYtMjI5LjU1Mi0xMjcuNzkyLTIyOS41NTItMjQ1LjQ0IDAtNTYuOTQ0IDE4LjgtMTA5LjUwNCA1NS44MDgtMTUxLjg1Ni0yMy40MDgtNjkuODg4IDIuMjU2LTEyOS40NzIgNS42MTYtMTM4LjQxNiA2Ni45MjgtNi4wOCAxMzYuNTEyIDQ3Ljg3MiAxNDEuODcyIDUyLjEyOCAzOC4xMjgtMTAuMTkyIDgxLjUyLTE1Ljc2IDEzMC4wNjQtMTUuNzYgNDguODQ4IDAgOTIuNDggNS42MzIgMTMwLjc4NCAxNS45NTIgMTMuMDcyLTkuOTUyIDc3LjU4NC01Ni4xOTIgMTM5LjgyNC01MC41NDQgMy4zMTIgOC44NDggMjguNDE2IDY3LjE2OCA2LjQgMTM2LjAxNiAzNy4zNzYgNDIuNDY0IDU2LjM1MiA5NS4yNjQgNTYuMzUyIDE1Mi40OCAwIDExNy44ODgtNjcuOTUyIDIxNy4wMDgtMjMwLjUyOCAyNDUuNiAyNy4xNTIgMjYuNzM2IDQzLjk4NCA2My45MiA0My45ODQgMTA1LjA0bDAgMTI5LjcxMmMwLjg2NCAxMC4zMzYgMCAyMC43MzYgMTcuMzYgMjAuNzM2IDIwNC4xMTItNjguNzUyIDM1MS4xMi0yNjEuNzEyIDM1MS4xMi00ODkuMDA4QzEwMzEuNzQ0IDIzMC45NiA4MDAuNzg0IDAgNTE1Ljg3MiAwIDIzMC45NiAwIDAgMjMwLjk2IDAgNTE1Ljg3MkwwIDUxNS44NzJ6TTAgNTE1Ljg3MiIgcC1pZD0iNDI3MSIgZmlsbD0iI2ZmZmZmZiI+PC9wYXRoPjwvc3ZnPg=="
                         alt="GitHub"></a>
        </div>
    </div>
</template>

<script>
  import qs from 'qs'

  const OK = 0  // 成功状态
  export default {
    data () {
      return {
        formValidate: {
          username: '',
          password: ''
        },
        ruleValidate: {
          username: [
            {
              required: true,
              message: '请输入用户名',
              trigger: 'blur'
            }
          ],
          password: [
            {
              required: true,
              message: '请输入密码',
              trigger: 'blur'
            }
          ]
        }
      }
    },
    methods: {
      handleSubmit (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
//            const params = new URLSearchParams()
//            params.append('username', this.formValidate.username)
//            params.append('password', this.formValidate.password)
            const params = qs.stringify({
              'username': this.formValidate.username,
              'password': this.formValidate.password
            })
            this.$http.post('/api/admin/login', params)
              .then((response) => {
                if (response.data.err === OK) {
                  this.$Message.success('登录成功!')
                  const data = {
                    username: this.formValidate.username,
                    token: response.data.token
                  }
                  this.$store.dispatch('login', data)
                  // dispatch action，从action commit 到mutation更新登录状态
//                  this.$store.dispatch('login', this.formValidate.username);
                  this.$router.push('/platform')
                } else {
                  this.$Message.error('帐号或密码有误!')
                }
              })
              .catch(() => {
                this.$Message.error('网络请求有误，请稍后重试!')
              })
          } else {
            this.$Message.error('表单填写有误!')
          }
        })
      }
    }
  }
</script>

<style>
    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #318fce;
        background: linear-gradient(to bottom right, #318fce 0%, #48e7ef 100%);
    }

    .login-wrapper .login-form {
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, .2);
        border-radius: 2px;
        padding: 30px 20px;
    }

    .login-wrapper .title {
        font-size: 18px;
        font-weight: 700;
        padding-bottom: 10px;
    }

    .login-wrapper .form-area {
        width: 300px;
    }

    .login-wrapper .login-logo {
        position: absolute;
        top: 10px;
        left: 30px;
        width: 250px;
        height: 75px;
        background: url("../../assets/logo.png") no-repeat center;
        background-size: cover;
    }

    .login-wrapper .login-github {
        position: absolute;
        top: 20px;
        right: 30px;
        margin: 20px 0;
        color: #fff;
        vertical-align: middle;
    }
    .icon-github {
        display: inline-block;
    }
    .icon-github img{
        width: 20px;
        height: auto;
    }


</style>
