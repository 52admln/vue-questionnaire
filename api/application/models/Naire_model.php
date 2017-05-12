<?php

class Naire_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->database();
	}

	// 获取用户
	public function get_naires()
	{
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
			"status" => $naire[0]["n_status"]
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
		}

		return array("err" => 0, "data" => $result);

	}

}
