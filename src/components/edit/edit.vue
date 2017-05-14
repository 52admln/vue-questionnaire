<template>
  <div class="edit">
    <div class="add-button">
      <Button type="primary" @click="addRadio">单选题</Button>
      <Button type="primary" @click="addCheckbox">多选题</Button>
      <Button type="primary" @click="addTextarea">文本题</Button>
    </div>

    <questionList :question-list="this.questionList" :is-preview="true">
      <Row>
        <Col span="24">
        <Button>保存问卷</Button>
        <Button type="success">发布问卷</Button>
        </Col>
      </Row>
    </questionList>

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
        <Form-item label="题目说明" prop="description">
          <Input v-model="addRadio_form.description" placeholder="请输入题目说明，可以为空"></Input>
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
              <i-switch v-model="option.isAddition">
                <span slot="open">有</span>
                <span slot="close">无</span>
              </i-switch>
            </div>
          </div>
        </Form-item>
        <Form-item label="必填项">
          <div class="option-addtion">
            <i-switch v-model="addRadio_form.isRequired">
              <span slot="open">是</span>
              <span slot="close">否</span>
            </i-switch>
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
        <Form-item label="题目说明" prop="description">
          <Input v-model="addCheckbox_form.description" placeholder="请输入题目说明，可以为空"></Input>
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
              <i-switch v-model="option.isAddition" disabled>
                <span slot="open">有</span>
                <span slot="close">无</span>
              </i-switch>
            </div>
          </div>
        </Form-item>
        <Form-item label="必填项">
          <div class="option-addtion">
            <i-switch v-model="addCheckbox_form.isRequired">
              <span slot="open">是</span>
              <span slot="close">否</span>
            </i-switch>
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
        <Form-item label="题目说明" prop="description">
          <Input v-model="addTextarea_form.description" placeholder="请输入题目说明，可以为空"></Input>
        </Form-item>
        <Form-item label="必填项">
          <div class="option-addtion">
            <i-switch v-model="addTextarea_form.isRequired">
              <span slot="open">是</span>
              <span slot="close">否</span>
            </i-switch>
          </div>
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
  import questionList from '@/components/common/questionList/questionList'

  export default {
    data () {
      return {
        addRadio_modal: false,
        addRadio_loading: false,
        addRadio_form: {},
        addCheckbox_modal: false,
        addCheckbox_loading: false,
        addCheckbox_form: {},
        addTextarea_modal: false,
        addTextarea_loading: false,
        addTextarea_form: {}
      }
    },
    computed: {
      questionList () {
        let data = this.$store.getters.questionList
        return JSON.parse(data)
      }
    },
    methods: {
      // 新建题目
      addRadio () {
        console.log('addRadio')
        this.addRadio_modal = true
        const radioQues = {
          title: '单选题目',
          options: [],
          description: '',
          type: '单选',
          isRequired: true,
          selectContent: '',
          additional: ''
        }
        this.addRadio_form = Object.assign({}, radioQues)
        const tempData = {
          content: '选项1',
          isAddition: false
        }
        this.addRadio_form.options.splice(0, this.addRadio_form.options.length, Object.assign({}, tempData))
      },
      addCheckbox () {
        console.log('addCheckbox')
        this.addCheckbox_modal = true
        const checkboxQues = {
          title: '多选题目',
          options: [],
          description: '',
          type: '多选',
          isRequired: true,
          selectMultipleContent: []
        }
        this.addCheckbox_form = Object.assign({}, checkboxQues)
        const tempData = {
          content: '选项1',
          isAddition: false
        }
        this.addCheckbox_form.options.splice(0, this.addCheckbox_form.options.length, Object.assign({}, tempData))
      },
      addTextarea () {
        console.log('addTextarea')
        this.addTextarea_modal = true
        const TextareaQues = {
          title: '文本题目',
          description: '',
          type: '文本',
          isRequired: true,
          selectContent: ''
        }
        this.addTextarea_form = Object.assign({}, TextareaQues)
      },
      // 新增选项
      addOption (source) {
        const tempData = {
          content: '选项1',
          isAddition: false
        }
        source.push(Object.assign({}, tempData))
      },
      // 删除选项
      delOption (source, index) {
        if (source.length > 1) {
          source.splice(index, 1)
        } else {
          this.$Message.warning('最后一个啦，不要删除哦')
        }
      },
      // 提交题目
      submitRadio (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            console.log(this.addRadio_form)
            const data = Object.assign({}, this.addRadio_form)
            this.$store.dispatch('addQuestion', data)
            this.addRadio_modal = false
          } else {
            this.$Message.error('表单填写有误!')
          }
        })
      },
      submitCheckbox (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            console.log(this.addCheckbox_form)
            const data = Object.assign({}, this.addCheckbox_form)
            this.$store.dispatch('addQuestion', data)
            this.addCheckbox_modal = false
          } else {
            this.$Message.error('表单填写有误!')
          }
        })
      },
      submitTextarea (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            console.log(this.addTextarea_form)
            const data = Object.assign({}, this.addTextarea_form)
            this.$store.dispatch('addQuestion', data)
            this.addTextarea_modal = false
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
      this.$store.dispatch('getQuestionList')
      console.log(this.$store.getters.questionList)
      // 通过 JSON 序列化将数组不再为引用，避免出现在 store 外修改 state 的内容
      let data = JSON.stringify(this.$store.getters.questionList)
      this.questionList = JSON.parse(data)
    },
    components: {
      questionList
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
