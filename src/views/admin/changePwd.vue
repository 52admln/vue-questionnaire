<template>
    <div class="change-pwd">
        <Form ref="formCustom" :model="formCustom" :rules="ruleCustom" :label-width="80">
            <Form-item label="原密码" prop="oldppasswd">
                <Input type="password" v-model="formCustom.oldppasswd"></Input>
            </Form-item>
            <Form-item label="密码" prop="passwd">
                <Input type="password" v-model="formCustom.passwd"></Input>
            </Form-item>
            <Form-item label="确认密码" prop="passwdCheck">
                <Input type="password" v-model="formCustom.passwdCheck"></Input>
            </Form-item>
            <Form-item>
                <Button type="primary" @click="handleSubmit('formCustom')">提交</Button>
                <Button type="ghost" @click="handleReset('formCustom')" style="margin-left: 8px">重置</Button>
            </Form-item>
        </Form>
    </div>
</template>

<script>
  export default {
    data () {
      const validatePass = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请输入密码'))
        } else {
          if (this.formCustom.passwdCheck !== '') {
            // 对第二个密码框单独验证
            this.$refs.formCustom.validateField('passwdCheck')
          }
          callback()
        }
      }
      const validatePassCheck = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请再次输入密码'))
        } else if (value !== this.formCustom.passwd) {
          callback(new Error('两次输入密码不一致!'))
        } else {
          callback()
        }
      }
      const validateOldPwd = (rule, value, callback) => {
        if (value === '') {
          return callback(new Error('原密码不能为空'))
        } else {
          callback()
        }
      }

      return {
        formCustom: {
          passwd: '',
          passwdCheck: '',
          oldppasswd: ''
        },
        ruleCustom: {
          passwd: [
            {validator: validatePass, trigger: 'blur'}
          ],
          passwdCheck: [
            {validator: validatePassCheck, trigger: 'blur'}
          ],
          oldppasswd: [
            {validator: validateOldPwd, trigger: 'blur'}
          ]
        }
      }
    },
    methods: {
      handleSubmit (name) {
        //  送往后台 修改 oldpwd , newpassword
        this.$refs[name].validate((valid) => {
          if (valid) {
            const params = {
              'oldpwd': this.formCustom.oldppasswd,
              'newpwd': this.formCustom.passwd
            }
            this.$http.post('/api/admin/changepwd', params)
              .then((response) => {
                console.log(response.data)
                // 影响行数大于0
                if (response.data.err === 0 && response.data.data > 0) {
                  this.$Message.success('修改成功')
                  // 重置表单
                  this.$refs[name].resetFields()
                  this.$store.dispatch('logout')
                  this.$Message.warning('请重新登录!')
                  this.$router.push('/login')
                } else {
                  this.$Message.error('修改失败，请重试')
                }
              })
              .catch((error) => {
                console.log(error)
                this.$Message.error('修改失败，请重试')
              })
          } else {
            this.$Message.error('表单验证失败!')
          }
        })
      },
      handleReset (name) {
        this.$refs[name].resetFields()
      }
    }
  }
</script>

<style>
    .change-pwd {
        width: 50%;
    }
</style>
