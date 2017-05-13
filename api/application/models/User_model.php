<?php

class User_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->database();
	}

	public function upload($data) {
		// 如果学号冲突，则不添加此用户
		$is_exist = $this->db->get_where('users', array('u_number' => $data[4]));
		if ($is_exist->num_rows() > 0) {
			return 0;
		}
		$insert_data = array(
			'u_major' => $data[0],
			'u_name' =>  $data[1],
			'u_sex' =>  $data[2],
			'u_class' =>  $data[3],
			'u_number' => $data[4],
			'u_birthday' => $data[5],
			'u_nation' => $data[6],
			'u_identity' => $data[7],
			'u_password' => sha1($data[4])
		);

		$this->db->insert('users', $insert_data);
		return $this->db->affected_rows();
	}

}
