<template>
  <div class="new">
    <Row>
      <Col span="11">
        <Form :model="formItem" :label-width="80">
          <Form-item label="问卷名">
            <Input v-model="formItem.title" placeholder="问卷名"></Input>
          </Form-item>
          <Form-item label="截止时间">
            <Date-picker type="date" placeholder="选择日期" v-model="formItem.deadline" :editable="false" :options="dateOption"></Date-picker>
          </Form-item>
          <Form-item label="问卷介绍">
            <Input v-model="formItem.intro"
                   type="textarea"
                   :autosize="{minRows: 7,maxRows: 10}"
                   placeholder="请输入问卷介绍..."></Input>
          </Form-item>
          <Form-item>
            <Button type="primary" @click="handleSubmit">创建</Button>
          </Form-item>
        </Form>
      </Col>
    </Row>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        formItem: {
          title: '',
          deadline: '',
          intro: ''
        },
        dateOption: {
          disabledDate (date) {
            return date && date.valueOf() < Date.now() - 86400000
          }
        }
      }
    },
    methods: {
      handleSubmit () {
        const topic = {
          topic: []
        }
        this.formItem.deadline = new Date(this.formItem.deadline).getTime()
        console.log(new Date(this.formItem.deadline).getTime())

        let newNaire = Object.assign({}, topic, this.formItem)
        console.log(newNaire)
        this.$store.dispatch('createNaire', newNaire)
        this.$router.push('/platform/new/edit')
      }
    }
  }
</script>

<style>

</style>
