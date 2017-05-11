<template>
  <Row class="question-wrapper">
    <Col span="24" class="question-list">
    <Row type="flex" justify="start" align="top" v-for="(topic, index) in questionList" :key="topic.q_id">
      <Col span="6" style="width: 60px;">
      <h2>Q{{ index + 1 }}:</h2></Col>
      <Col span="18">
      <h3>{{ topic.question }}{{topic.isRequired ? "（必填）" : "（选填）"}}</h3>
      <div class="question-options">
        <div class="option-item" v-if="topic.type === '单选'">
          <Radio-group v-model="topic.selectContent" vertical>
            <Radio :label="option.o_id" v-for="(option, opIndex) in topic.options" :key="option.o_id">
              <span>{{option.content}}</span>
              <Input v-model="topic.additional"
                     placeholder="请输入理由"
                     style="width: 300px"
                     :disabled="!(option.isAddition && topic.selectContent === option.o_id)"
                     v-if="option.isAddition"></Input>
            </Radio>
          </Radio-group>
        </div>
        <div class="option-item" v-if="topic.type === '多选'">
          <Checkbox-group v-model="topic.selectMultipleContent" class="checkbox-list">
            <Checkbox
              :label="option.o_id"
              v-for="(option, opIndex) in topic.options"
              :key="option.o_id">
              <span>{{option.content}}</span>
            </Checkbox>
          </Checkbox-group>
        </div>
        <div class="option-item" v-if="topic.type === '文本'">
          <Input v-model="topic.selectContent" type="textarea" :autosize="{minRows: 2,maxRows: 5}"
                 placeholder="请输入..."></Input>
        </div>
      </div>
      </Col>
    </Row>
    <slot></slot>
    </Col>
  </Row>
</template>

<script>
  export default {
    props: ['questionList'],
    created () {
      console.log('imported successfully', this.questionList)
    }
  }
</script>

<style scoped>
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

  .option-item {
    padding: 5px 0;
  }
</style>
