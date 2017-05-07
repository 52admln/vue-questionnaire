<template>
  <div class="new">
    <Row>
      <Col span="11">
        <Form :model="formItem" :label-width="80">
          <Form-item label="问卷名">
            <Input v-model="formItem.input" placeholder="问卷名"></Input>
          </Form-item>
          <Form-item label="截止时间">
            <Date-picker type="date" placeholder="选择日期" v-model="formItem.date" :editable="false"></Date-picker>
          </Form-item>
          <Form-item label="问卷介绍">
            <Input v-model="formItem.textarea"
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
          input: '',
          date: '',
          textarea: ''
        }
      }
    },
    methods: {
      handleSubmit () {
        this.$router.push('/platform/new/edit')
        console.log(this.formItem.date.getTime())
      }
    }
  }
</script>

<style>

</style>
