<?php

class Naire_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->database();
	}

	// 获取问卷详细信息
	public function get_naires()
	{
		// todo 获取参数 naire id
		$naire = $this->db->query("select * from naire where naire.n_id = 1")
			->result_array();
		$questions = $this->db->query("select * from question where question.n_id = 1")
			->result_array();
		$options = $this->db->query("select * from options where options.n_id = 1")
			->result_array();

//		echo var_dump($naire);
//		echo var_dump($questions);
//		echo var_dump($options);

		$result = array(
			"n_id" => $naire[0]["n_id"],
			"title" => $naire[0]["n_title"],
			"creattime" => $naire[0]["n_creattime"],
			"deadline" => $naire[0]["n_deadline"],
			"status" => $naire[0]["n_status"],
			"intro" => $naire[0]["n_intro"]
		);
		foreach ($questions as $questionkey => $questionval) {
//		  echo var_dump($val);
			$temp = [];
			foreach ($options as $optionitem => $optionval) {
				if ($questionval['q_id'] == $optionval['q_id']) {
					$temp[] = array(
						"o_id" => $optionval['o_id'],
						"content" => $optionval['o_value'],
						"isAddition" => $optionval['o_isaddtion']
					);
				}
			}
			if ($questionval["q_type"] == '单选') {
				$result['topic'][] = array(
					"q_id" => $questionval["q_id"],
					"question" => $questionval["q_content"],
					"isRequired" => $questionval["q_isrequire"],
					"type" => $questionval["q_type"],
					"description" => $questionval["q_description"],
					"selectContent" => "",
					"additional" => "",
					"options" => $temp
				);
			} else if ($questionval["q_type"] == '多选') {
				$result['topic'][] = array(
					"q_id" => $questionval["q_id"],
					"question" => $questionval["q_content"],
					"isRequired" => $questionval["q_isrequire"],
					"type" => $questionval["q_type"],
					"description" => $questionval["q_description"],
					"selectMultipleContent" => array(),
					"additional" => "",
					"options" => $temp
				);
			} else if ($questionval["q_type"] == '文本') {
				$result['topic'][] = array(
					"q_id" => $questionval["q_id"],
					"question" => $questionval["q_content"],
					"isRequired" => $questionval["q_isrequire"],
					"type" => $questionval["q_type"],
					"description" => $questionval["q_description"],
					"selectContent" => "",
				);
			}
		}

		return array("err" => 0, "data" => $result);

	}

	public function get_naire_list()
	{

		$query = $this->db->get('naire');
		if (!$query) {
			$err = 1;
		} else {
			$err = 0;
		}
		return array("err" => $err, "data" => $query->result_array());
	}

	public function save_naire()
	{
		// JSON 反序列化
		$naire = json_decode($this->input->raw_input_stream, true)['naire'];
		$status = json_decode($this->input->raw_input_stream, true)['status'];

		if ($status == 'create') {
			// 执行插入操作
			if ($naire['deadline'] == '' || $naire['title'] == '' || $naire['status'] == '') {
				return array("err" => 1, "data" => '表单项必填字段不能为空');
			}
			$insert_naire_data = array(
				'n_deadline' => $naire['deadline'],
				'n_title' => $naire['title'],
				'n_status' => $naire['status'],
				'n_intro' => $naire['intro']
			);
			$this->db->insert('naire', $insert_naire_data);
			$naire_id = $this->db->insert_id();
			// 遍历题目
			foreach ($naire['topic'] as $topickey => $topicval) {

				// 题目内容
				if ($topicval['question'] == '' || $topicval['type'] == '' || $topicval['isRequired'] == '') {
					return array("err" => 1, "data" => '表单项必填字段不能为空');
				}
				// print_r($topicval['question']);
				$insert_question_data = array(
					'q_content' => $topicval['question'],
					'q_type' => $topicval['type'],
					'n_id' => $naire_id,
					'q_isrequire' => $topicval['isRequired'],
					'q_description' => $topicval['description']
				);

				$this->db->insert('question', $insert_question_data);
				$question_id = $this->db->insert_id();
				if (!empty($topicval['options']) && $topicval['type'] != '文本') {
					// 遍历选项
					foreach ($topicval['options'] as $optionkey => $optionval) {
						// 选项内容 $optionval['content']
						// 选项是否需要填写附加内容 $optionval['isAddition']
						if ($optionval['content'] == '' || $optionval['isAddition'] == '') {
							return array("err" => 1, "data" => '表单项必填字段不能为空');
						}
						$insert_option_data = array(
							'o_value' => $optionval['content'],
							'n_id' => $naire_id,
							'q_id' => $question_id,
							'o_isaddtion' => $optionval['isAddition']
						);
						$this->db->insert('options', $insert_option_data);
						//print_r($optionval['isAddition'] == 1 ? 'true' : 'false');
					}
				}

			}
			return array("err" => 0, "data" => '新建问卷成功');

		} else {
			// 执行更新操作
		}

		return array("err" => 0, "data" => '新建问卷成功');
	}
}
