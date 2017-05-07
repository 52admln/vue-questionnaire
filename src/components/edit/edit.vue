<template>
  <div class="edit">
    <div class="add-button">
      <Button type="primary" @click="addRadio">单选题</Button>
      <Button type="primary" @click="addCheckbox">多选题</Button>
      <Button type="primary" @click="addTextarea">文本题</Button>
    </div>

    <Row class="question-wrapper">
      <Col span="18" class="question-list">
      <Row type="flex" justify="start" align="top" v-for="(_topic, index) in this.topic" :key="_topic.q_id">
        <Col span="6" style="width: 60px;">
        <h2>Q{{ _topic.order }}:</h2></Col>
        <Col span="18">
        <h3>{{ _topic.question }}{{_topic.isRequired ? "（必填）" : "（选填）"}}</h3>
        <div class="question-options">
          <div class="option-item" v-if="_topic.type === '单选'">
            <Radio-group v-model="_topic.selectContent" vertical>
              <Radio :label="option.o_id" v-for="(option, opIndex) in _topic.options" :key="option.o_id">
                <span>{{option.content}}</span>
                <Input v-model="_topic.additional"
                       placeholder="请输入理由"
                       style="width: 300px"
                       :disabled="!(option.isAddition && _topic.selectContent === option.o_id)"
                       v-if="option.isAddition"></Input>
              </Radio>
            </Radio-group>
          </div>
          <div class="option-item" v-if="_topic.type === '多选'">
            <Checkbox-group v-model="_topic.selectMultipleContent" class="checkbox-list">
              <Checkbox
                :label="option.o_id"
                v-for="(option, opIndex) in _topic.options"
                :key="option.o_id">
                <span>{{option.content}}</span>
              </Checkbox>
            </Checkbox-group>
          </div>
          <div class="option-item" v-if="_topic.type === '文本'">
            <Input v-model="_topic.selectContent" type="textarea" :autosize="{minRows: 2,maxRows: 5}"
                   placeholder="请输入..."></Input>
          </div>
        </div>
        </Col>
      </Row>
      </Col>
    </Row>


    <!-- addRadio -->
    <Modal v-model="addRadio_modal"
           title="添加单选题"
           :mask-closable="false"
    >
      <!-- form表单-->
      <Form ref="addRadio" v-model="addRadio_form" :label-width="80" class="form">
        <Form-item label="题目" prop="title">
          <Input v-model="addRadio_form.title" placeholder="请输入题目内容"></Input>
        </Form-item>
        <Form-item label="选项" prop="title">
          <div class="option-item" v-for="(option, index) in addRadio_form.options">
            <Row>
              <Col span="18">
              <Input v-model="option.content" :key="index" placeholder="请输入选项内容"
                     style="display:inline-block"></Input></Col>
              <Col span="6">
              <div class="option-btn">
                <Button type="success" icon="plus-round" size="small"
                        @click="addOption(addRadio_form.options)"></Button>
                <Button type="warning" icon="close" size="small"
                        @click="delOption(addRadio_form.options,index)"></Button>
              </div>
              </Col>
            </Row>
            <div class="option-addtion">
              是否有附加说明内容：
              <i-switch :model="option.isAddition">
                <span slot="open">有</span>
                <span slot="close">无</span>
              </i-switch>
            </div>
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
          <div class="option-item" v-for="(option, index) in addCheckbox_form.options">
            <Row>
              <Col span="18">
              <Input v-model="option.content" :key="index" placeholder="请输入选项内容"
                     style="display:inline-block"></Input></Col>
              <Col span="6">
              <div class="option-btn">
                <Button type="success" icon="plus-round" size="small"
                        @click="addOption(addCheckbox_form.options)"></Button>
                <Button type="warning" icon="close" size="small"
                        @click="delOption(addCheckbox_form.options,index)"></Button>
              </div>

              </Col>
            </Row>
            <div class="option-addtion">
              是否有附加说明内容：
              <i-switch :model="option.isAddition" disabled>
                <span slot="open">有</span>
                <span slot="close">无</span>
              </i-switch>
            </div>
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
          title: '单选题目',
          options: []
        },
        addCheckbox_modal: false,
        addCheckbox_loading: false,
        addCheckbox_form: {
          title: '多选题目',
          options: []
        },
        addTextarea_modal: false,
        addTextarea_loading: false,
        addTextarea_form: {
          title: '文本题目'
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
        const tempData = {
          content: '选项1',
          isAddition: false
        }
        this.addRadio_form.options.splice(0, this.addRadio_form.options.length, tempData)
      },
      addCheckbox () {
        console.log('addCheckbox')
        this.addCheckbox_modal = true
        const tempData = {
          content: '选项1',
          isAddition: false
        }
        this.addCheckbox_form.options.splice(0, this.addCheckbox_form.options.length, tempData)
      },
      addTextarea () {
        console.log('addTextarea')
        this.addTextarea_modal = true
      },
      addOption (source) {
        const tempData = {
          content: '选项1',
          isAddition: false
        }
        source.push(tempData)
      },
      delOption (source, index) {
        if (source.length > 1) {
          source.splice(index, 1)
        } else {
          this.$Message.warning('最后一个啦，不要删除哦')
        }
      },
      submitRadio (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            console.log(this.addRadio_form)
          } else {
            this.$Message.error('表单填写有误!')
          }
        })
      },
      submitCheckbox (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            console.log(this.addCheckbox_form)
          } else {
            this.$Message.error('表单填写有误!')
          }
        })
      },
      submitTextarea (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            console.log(this.addTextarea_form)
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
          selectContent: '',
          additional: '',
          options: [
            {
              content: '选项1',
              o_id: 1,
              isAddition: true
            },
            {
              content: '选项1',
              o_id: 2,
              isAddition: false
            }
          ]
        },
        {
          question: '多选问题2',
          order: 2,
          q_id: 2,
          isRequired: true,
          type: '多选',
          selectMultipleContent: [],
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
          question: '多选多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3多选问题3问题3',
          order: 22,
          q_id: 211,
          isRequired: true,
          type: '多选',
          selectMultipleContent: [],
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
          selectContent: ''
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
    line-height: 30px;
  }

</style>
