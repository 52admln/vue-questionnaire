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
		// 获取参数 naire id
		// JSON 反序列化
		$n_id = json_decode($this->input->raw_input_stream, true)['n_id'];
		if ($n_id == '') {
			return array("err" => 1, "data" => "请传入参数值");
		}
		$naire = $this->db->query("select * from naire where naire.n_id = {$n_id}")
			->result_array();
		$questions = $this->db->query("select * from question where question.n_id = {$n_id}")
			->result_array();
		$options = $this->db->query("select * from options where options.n_id = {$n_id}")
			->result_array();

//		echo var_dump($naire);
//		echo var_dump($questions);
//		echo var_dump($options);
		if (empty($naire) || empty($questions) || empty($options)) {
			return array("err" => 1, "data" => "未获取到相应问卷");
		}
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
						"isAddition" => $optionval['o_isaddtion'] == "1" ? true : false
					);
				}
			}
			if ($questionval["q_type"] == '单选') {
				$result['topic'][] = array(
					"q_id" => $questionval["q_id"],
					"question" => $questionval["q_content"],
					"isRequired" => $questionval["q_isrequire"] == "1" ? true : false,
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
					"isRequired" => $questionval["q_isrequire"] == "1" ? true : false,
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
					"isRequired" => $questionval["q_isrequire"] == "1" ? true : false,
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
			if (is_null($naire['deadline']) || is_null($naire['title']) || is_null($naire['status'])) {
				return array("err" => 1, "data" => "问卷(naire)必填字段不能为空");
			}
			$insert_naire_data = array(
				'n_deadline' => $naire['deadline'],
				'n_title' => $naire['title'],
				'n_status' => $naire['status'],
				'n_intro' => $naire['intro'],
				'n_creattime' => time()
			);
			$this->db->insert('naire', $insert_naire_data);
			$naire_id = $this->db->insert_id();
			// 遍历题目
			foreach ($naire['topic'] as $topickey => $topicval) {

				// 题目内容
				if (is_null($topicval['question']) || is_null($topicval['type']) || is_null($topicval['isRequired'])) {
					return array("err" => 1, "data" => '问题(question)必填字段不能为空');
				}
				// print_r($topicval['question']);
				$insert_question_data = array(
					'q_content' => $topicval['question'],
					'q_type' => $topicval['type'],
					'n_id' => $naire_id,
					'q_isrequire' => $topicval['isRequired'] == "true" ? 1 : 0,
					'q_description' => $topicval['description']
				);

				$this->db->insert('question', $insert_question_data);
				$question_id = $this->db->insert_id();
				if (!empty($topicval['options']) && $topicval['type'] != '文本') {
					// 遍历选项
					foreach ($topicval['options'] as $optionkey => $optionval) {
						// 选项内容 $optionval['content']
						// 选项是否需要填写附加内容 $optionval['isAddition']
						if (is_null($optionval['content']) || is_null($optionval['isAddition'])) {
							return array("err" => 1, "data" => '选项(option)必填字段不能为空');
						}
						$insert_option_data = array(
							'o_value' => $optionval['content'],
							'n_id' => $naire_id,
							'q_id' => $question_id,
							'o_isaddtion' => $optionval['isAddition'] == "true" ? 1 : 0
						);
						$this->db->insert('options', $insert_option_data);
						//print_r($optionval['isAddition'] == 1 ? 'true' : 'false');
					}
				}

			}
			return array("err" => 0, "data" => '新建问卷成功');

		} else {
			// todo 执行更新操作
		}

		return array("err" => 0, "data" => '新建问卷成功');
	}

	public function submit_naire()
	{
		$result = json_decode($this->input->raw_input_stream, true)['result'];
		print_r($result);
	}


}
