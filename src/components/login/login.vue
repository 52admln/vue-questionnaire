<template>
  <div class="login-wrapper">
    <div class="login-form">
      <h3 class="title">微型问卷系统后台管理</h3>
      <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" label-position="top" class="form-area">
        <Form-item label="用户名" prop="username">
          <Input v-model="formValidate.username"></Input>
        </Form-item>
        <Form-item label="密码" prop="password">
          <Input type="password" v-model="formValidate.password"></Input>
        </Form-item>
        <Form-item>
          <Button type="primary" html-type="submit" @click.prevent="handleSubmit('formValidate')" long>立即登录</Button>
        </Form-item>
      </Form>
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
            const params = qs.stringify({ 'username': this.formValidate.username, 'password': this.formValidate.password })
            this.$http.post('/api/admin/login', params)
              .then((response) => {
                console.log(response.data)
                if (response.data.err === OK) {
                  this.$Message.success('登录成功!')
                  console.log(response.data)
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
              .catch((error) => {
                console.log(error)
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
</style>
