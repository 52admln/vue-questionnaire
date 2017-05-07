<template>
  <div class="edit">
    <div class="add-button">
      <Button type="primary" @click="addRadio">单选题</Button>
      <Button type="primary" @click="addCheckbox">多选题</Button>
      <Button type="primary" @click="addTextarea">文本题</Button>
    </div>

    <Row v-for="(topic, index) in this.topic">
      <Col span="12" class="question-list">
        <div class="side">
          <h2>Q{{ topic.order }}:</h2>
        </div>

        <div class="content">
          <h3>{{ topic.question }}{{topic.isRequired ? "（必填）" : "（选填）"}}</h3>
          <div class="question-options">
            <div class="option-item" v-if="topic.type === '单选'" >
              <Radio-group v-model="radio" v-for="(option, opIndex) in topic.options" vertical>
                <Radio :label="option.o_id" :key="option._id">
                  <span>{{option.content}}</span>
                </Radio>
              </Radio-group>
            </div>
            <div class="option-item" v-if="topic.type === '多选'" >
              <Checkbox-group v-model="checkbox" class="checkbox-list" v-for="(option, opIndex) in topic.options" >
                <Checkbox :label="option.o_id" :key="option._id">
                  <span>{{option.content}}</span>
                </Checkbox>
              </Checkbox-group>
            </div>
            <div class="option-item" v-if="topic.type === '文本'" >
              <Input v-model="value8" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入..."></Input>
            </div>
          </div>
        </div>
      </Col>
    </Row>


    <!-- addRadio -->
    <Modal v-model="addRadio_modal"
           title="添加单选题"
           :mask-closable="false"
    >
      <!-- form表单-->
      <Form ref="addRadio" :model="addRadio_form" :label-width="80" class="form">
        <Form-item label="题目" prop="title">
          <Input v-model="addRadio_form.title" placeholder="请输入题目内容"></Input>
        </Form-item>
        <Form-item label="选项" prop="title">
          <div class="option-item">
            <Row>
              <Col span="18">
              <Input v-model="addRadio_form.title" placeholder="请输入选项内容" style="display:inline-block"></Input></Col>
              <Col span="6">
              <div class="option-btn">
                <Button type="success" icon="plus-round" size="small"></Button>
                <Button type="warning" icon="close" size="small"></Button>
              </div>

              </Col>
            </Row>
            <div class="option-addtion">
              是否有附加说明内容：
              <i-switch>
                <span slot="open">有</span>
                <span slot="close">无</span>
              </i-switch></div>
          </div>
          <div class="option-item">
            <Row>
              <Col span="18">
              <Input v-model="addRadio_form.title" placeholder="请输入选项内容" style="display:inline-block"></Input></Col>
              <Col span="6">
              <div class="option-btn">
                <Button type="success" icon="plus-round" size="small"></Button>
                <Button type="warning" icon="close" size="small"></Button>
              </div>
              </Col>
            </Row>
            <div class="option-addtion">
              是否有附加说明内容：
              <i-switch>
                <span slot="open">有</span>
                <span slot="close">无</span>
              </i-switch></div>
          </div>
        </Form-item>
      </Form>
      <div slot="footer">
        <Button type="primary" :loading="addRadio_loading" @click="submitRadio('addRadio')">提交</Button>
        <Button @click="closeRadioModal">关闭</Button>
      </div>
    </Modal>
    <!-- addCheckbox -->
    <Modal v-model="addCheckbox_modal"
           title="添加多选题"
           :mask-closable="false"
    >
      <!-- form表单-->
      <Form ref="addCheckbox" :model="addCheckbox_form" :label-width="80" class="form">
        <Form-item label="题目" prop="title">
          <Input v-model="addCheckbox_form.title" placeholder="请输入题目内容"></Input>
        </Form-item>
        <Form-item label="选项" prop="title">
          <div class="option-item">
            <Row>
              <Col span="18">
              <Input v-model="addCheckbox_form.title" placeholder="请输入选项内容" style="display:inline-block"></Input></Col>
              <Col span="6">
              <div class="option-btn">
                <Button type="success" icon="plus-round" size="small"></Button>
                <Button type="warning" icon="close" size="small"></Button>
              </div>

              </Col>
            </Row>
            <div class="option-addtion">
              是否有附加说明内容：
              <i-switch>
                <span slot="open">有</span>
                <span slot="close">无</span>
              </i-switch></div>
          </div>
          <div class="option-item">
            <Row>
              <Col span="18">
              <Input v-model="addCheckbox_form.title" placeholder="请输入选项内容" style="display:inline-block"></Input></Col>
              <Col span="6">
              <div class="option-btn">
                <Button type="success" icon="plus-round" size="small"></Button>
                <Button type="warning" icon="close" size="small"></Button>
              </div>
              </Col>
            </Row>
            <div class="option-addtion">
              是否有附加说明内容：
              <i-switch>
                <span slot="open">有</span>
                <span slot="close">无</span>
              </i-switch></div>
          </div>
        </Form-item>
      </Form>
      <div slot="footer">
        <Button type="primary" :loading="addCheckbox_loading" @click="submitCheckbox('addCheckbox')">提交</Button>
        <Button @click="closeCheckboxModal">关闭</Button>
      </div>
    </Modal>
    <!-- addTextarea -->
    <Modal v-model="addTextarea_modal"
           title="添加文本题"
           :mask-closable="false"
    >
      <!-- form表单-->
      <Form ref="addTextarea" :model="addTextarea_form" :label-width="80" class="form">
        <Form-item label="题目" prop="title">
          <Input v-model="addTextarea_form.title" placeholder="请输入题目内容"></Input>
        </Form-item>
      </Form>
      <div slot="footer">
        <Button type="primary" :loading="addTextarea_loading" @click="submitTextarea('addTextarea')">提交</Button>
        <Button @click="closeTextareaModal">关闭</Button>
      </div>
    </Modal>

  </div>
</template>

<script>
  export default {
    data () {
      return {
        addRadio_modal: false,
        addRadio_loading: false,
        addRadio_form: {
          title: '',
          options: []
        },
        addCheckbox_modal: false,
        addCheckbox_loading: false,
        addCheckbox_form: {
          title: '',
          options: []
        },
        addTextarea_modal: false,
        addTextarea_loading: false,
        addTextarea_form: {
          title: ''
        },
        questionList: [],
        radio: 'apple',
        checkbox: [],
        value8: '',
        topic: []
      }
    },
    methods: {
      addRadio () {
        console.log('addRadio')
        this.addRadio_modal = true
      },
      addCheckbox () {
        console.log('addCheckbox')
        this.addCheckbox_modal = true
      },
      addTextarea () {
        console.log('addTextarea')
        this.addTextarea_modal = true
      },
      submitRadio (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            console.log('success!!')
          } else {
            this.$Message.error('表单填写有误!')
          }
        })
      },
      submitCheckbox (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            console.log('success!!')
          } else {
            this.$Message.error('表单填写有误!')
          }
        })
      },
      submitTextarea (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            console.log('success!!')
          } else {
            this.$Message.error('表单填写有误!')
          }
        })
      },
      closeRadioModal () {
        this.addRadio_modal = false
      },
      closeCheckboxModal () {
        this.addCheckbox_modal = false
      },
      closeTextareaModal () {
        this.addTextarea_modal = false
      }
    },
    created () {
      this.topic = [
        {
          question: '问题1',
          order: 1,
          q_id: 1,
          isRequired: true,
          type: '单选',
          options: [
            {
              content: '选项1',
              o_id: 1,
              isAddition: true,
            },
            {
              content: '选项1',
              o_id: 2,
              isAddition: false,
            }
          ]
        },
        {
          question: '多选问题2',
          order: 2,
          q_id: 2,
          isRequired: true,
          type: '多选',
          options: [
            {
              content: '选项1',
              o_id: 3,
              isAddition: false
            },
            {
              content: '选项1',
              o_id: 4,
              isAddition: false
            }
          ]
        },
        {
          question: '多选问题3',
          order: 22,
          q_id: 211,
          isRequired: true,
          type: '多选',
          options: [
            {
              content: '选项1',
              o_id: 223,
              isAddition: false
            },
            {
              content: '选项1',
              o_id: 554,
              isAddition: false
            }
          ]
        },
        {
          question: '文本题3',
          order: 3,
          q_id: 3,
          isRequired: true,
          type: '文本',
          options: []
        }
      ]
    }
  }
</script>

<style>

  .question-list {
    padding: 15px 0;
    overflow: hidden;
  }

  .question-list .side {
    float: left;
  }

  .question-list .content {
    float: left;
    margin-left: 30px;
    min-width: 300px;
    max-width: 400px;
  }

  .checkbox-list label {
    display: block;
    height: 30px;
    line-height: 30px;
  }

  .question-options {
    padding: 5px 0;
  }

  .option-btn {
    padding-left: 10px;
  }
  .option-item {
    padding: 5px 0;
  }
  .option-addtion {
    height: 30px;
    line-height:30px;
   }

</style>
