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
		if (empty($naire) || empty($questions)) {
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
					"selectContent" => ""
				);
			}
		}

		return array("err" => 0, "data" => $result);

	}

	// 获取问卷列表
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

	// 保存问卷
	public function save_naire()
	{
		// JSON 反序列化
		$naire = json_decode($this->input->raw_input_stream, true)['naire'];
		$status = json_decode($this->input->raw_input_stream, true)['status'];

		if ($status == 'create') {
			// 执行插入操作
			if ($naire['deadline'] === '' || $naire['title'] === '' || $naire['status'] === '') {
				return array("err" => 1, "data" => "问卷(naire)必填字段不能为空");
			}
			$insert_naire_data = array(
				'n_deadline' => $naire['deadline'],
				'n_title' => trim($naire['title']),
				'n_status' => $naire['status'],
				'n_intro' => trim($naire['intro']),
				'n_creattime' => self::getMillisecond()
			);
			$this->db->insert('naire', $insert_naire_data);
			$naire_id = $this->db->insert_id();
			// 遍历题目
			foreach ($naire['topic'] as $topickey => $topicval) {

				// 题目内容
				if ($topicval['question'] === '' || $topicval['type'] === '' || $topicval['isRequired'] === '') {
					return array("err" => 1, "data" => '问题(question)必填字段不能为空');
				}
				// print_r($topicval['question']);
				$insert_question_data = array(
					'q_content' => trim($topicval['question']),
					'q_type' => $topicval['type'],
					'n_id' => $naire_id,
					'q_isrequire' => $topicval['isRequired'] == "true" ? 1 : 0,
					'q_description' => trim($topicval['description'])
				);

				$this->db->insert('question', $insert_question_data);
				$question_id = $this->db->insert_id();
				if (!empty($topicval['options']) && $topicval['type'] != '文本') {
					// 遍历选项
					foreach ($topicval['options'] as $optionkey => $optionval) {
						// 选项内容 $optionval['content']
						// 选项是否需要填写附加内容 $optionval['isAddition']
						if ($optionval['content'] === '' || $optionval['isAddition'] === '') {
							return array("err" => 1, "data" => '选项(option)必填字段不能为空');
						}
						$insert_option_data = array(
							'o_value' => trim($optionval['content']),
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
	}

	// 提交问卷
	public function submit_naire()
	{
		$result = json_decode($this->input->raw_input_stream, true)['result'];

		foreach ($result as $key => $val) {
//			[n_id] => 12
//            [q_id] => 41
//            [o_id] => 52
//            [o_addition] =>
			// todo 用户 u_id 获取
			// 如果是多选题，需要再次 foreach
			if (is_array($val['o_id'])) {
				foreach ($val['o_id'] as $o_key => $o_val) {
					$option_data = array(
						'n_id' => $val['n_id'],
						'u_id' => 1,
						'q_id' => $val['q_id'],
						'o_id' => $o_val,
						'o_addtion' => trim($val['o_addition']),
					);
					$query = $this->db->insert('result', $option_data);
					if (!$query) {
						return array("err" => 1, "data" => '写入数据发生错误');
					}
				}
			} else {
				$result_data = array(
					'n_id' => $val['n_id'],
					'u_id' => 1,
					'q_id' => $val['q_id'],
					'o_id' => is_null($val['o_id']) ? '' : $val['o_id'],
					'o_addtion' => $val['o_addition'],
				);
				$query = $this->db->insert('result', $result_data);
				if (!$query) {
					return array("err" => 1, "data" => '写入数据发生错误');
				}
			}

		}
		return array("err" => 0, "data" => '问卷提交成功');
	}

	// 删除问卷
	public function del_naire()
	{
		$n_id = $this->input->post_get('n_id', TRUE);
		// 删除多表中的数据
		$del_tables = array('naire', 'question', 'options', 'result');
		$this->db->where('n_id', $n_id);
		$this->db->delete($del_tables);
		$result = $this->db->affected_rows();

//		$this->db->query("DELETE FROM naire, question, options, result  WHERE n_id={$n_id}");
//		$rows = $this->db->affected_rows();

		return array('err' => 0, "data" => $result);
	}

	// 问卷统计
	public function statis_naire()
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
		if (empty($naire) || empty($questions)) {
			return array("err" => 1, "data" => "未获取到相应问卷");
		}
		// 先遍历 问卷表，拿到问卷id
		$result["naire"] = array(
			"n_id" => $naire[0]["n_id"],
			"title" => $naire[0]["n_title"],
			"creattime" => $naire[0]["n_creattime"],
			"deadline" => $naire[0]["n_deadline"],
			"status" => $naire[0]["n_status"],
			"intro" => $naire[0]["n_intro"]
		);
		// 再遍历题目表，拿到题目id，去遍历选项表
		foreach ($questions as $questionkey => $questionval) {
//		  echo var_dump($val);
			$temp = [];
			$charts = []; // 每个选项的个数

			// 用于图表显示
			// 查询该题目总调查人数
			// select *,count(*) as total from result where n_id = {$naire[0]["n_id"]} and q_id = {$questionval["q_id"]} group by q_id
			$total = $this->db->query("select *,count(*) as total from result where n_id = {$naire[0]['n_id']} and q_id = {$questionval['q_id']} group by q_id")->result_array()[0]["total"];

			foreach ($options as $optionitem => $optionval) {
				// 如果题目id 等于 选项表当中的题目id，则将该选项添加到临时数组中
				if ($questionval['q_id'] == $optionval['q_id']) {

					// 查询每个选项在数据库中的个数
					// select *,count(*) as total from result where n_id = {$naire[0]['n_id']} and q_id = {$questionval['q_id']} and o_id = {$optionval['o_id']}
					$count = $this->db->query("select *,count(*) as total from result where n_id = {$naire[0]['n_id']} and q_id = {$questionval['q_id']} and o_id = {$optionval['o_id']}")->result_array()[0]["total"];
					$charts[] = $count;
					$percent =( $count / $total * 100 ). "%" ;

					$temp[] = array(
						"o_id" => $optionval['o_id'],
						"content" => $optionval['o_value'],
						"isAddition" => $optionval['o_isaddtion'] == "1" ? true : false,
						"count" => $count,
						"percent" => $percent
					);
					$optionList[] = $optionval['o_value'];
				}
			}
			// 单选题
			if ($questionval["q_type"] == '单选') {
				$result['questions'][] = array(
					"q_id" => $questionval["q_id"],
					"question" => $questionval["q_content"],
					"isRequired" => $questionval["q_isrequire"] == "1" ? true : false,
					"type" => $questionval["q_type"],
					"description" => $questionval["q_description"],
					"selectContent" => "",
					"additional" => "",
					"options" => $temp,
					"charts" => $charts
				);
				// 多选题
			} else if ($questionval["q_type"] == '多选') {
				$result['questions'][] = array(
					"q_id" => $questionval["q_id"],
					"question" => $questionval["q_content"],
					"isRequired" => $questionval["q_isrequire"] == "1" ? true : false,
					"type" => $questionval["q_type"],
					"description" => $questionval["q_description"],
					"selectMultipleContent" => array(),
					"additional" => "",
					"options" => $temp,
					"charts" => $charts
				);
				// 文本题
			} else if ($questionval["q_type"] == '文本') {
				// 拿问答题提交内容
				$answerList = [];
				$answerData = $this->db->query("select * from result where n_id = {$naire[0]["n_id"]} and q_id = {$questionval["q_id"]} ")->result_array();
				foreach ($answerData as $item => $val) {
//					print_r($val["o_addtion"]);
					$answerList[] = array(
						"content" => $val["o_addtion"]
					);
				}
				$result['questions'][] = array(
					"q_id" => $questionval["q_id"],
					"question" => $questionval["q_content"],
					"isRequired" => $questionval["q_isrequire"] == "1" ? true : false,
					"type" => $questionval["q_type"],
					"description" => $questionval["q_description"],
					"selectContent" => "",
					"answerList" => $answerList
				);

			}
		}

		return array("err" => 0, "data" => $result);

	}


	// 获得毫秒级的时间戳
	private function getMillisecond()
	{
		list($t1, $t2) = explode(' ', microtime());
		return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
	}

}
