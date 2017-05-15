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
			if($questionval["q_type"] == '单选'){
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
			} else if($questionval["q_type"] == '多选') {
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
			} else if($questionval["q_type"] == '文本') {
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

	public function get_naire_list() {

		$query = $this->db->get('naire');
		if(!$query) {
			$err = 1;
		} else {
			$err = 0;
		}
		return array("err"=> $err, "data" => $query->result_array());
	}
}
