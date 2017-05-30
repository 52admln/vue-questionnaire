<?php

class User_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->database();
	}

	// 获取用户
	public function get_users()
	{
		// 如果传入用户ID,返回当前用户的信息
		$currentUser = $this->input->post_get('u_id', TRUE);
		// 参数1: $currentPage 当前页码, 参数2: $pageSize 每页显示条数
		// 如果有参数,则返回分页的数据,没有返回全部数据
		$currentPage = $this->input->post_get('current', TRUE);
		$pageSize = $this->input->post_get('page_size', TRUE);
		$total_query = $this->db->get('users');
		$total = $total_query->num_rows();

		// 如果传入用户ID,返回当前用户的信息
		if ($currentUser != '') {
			// 返回全部数据
			$query = $this->db->where('u_id', $currentUser)
				->get('users');
			if (!$query) {
				$error = 1; // ERROR
			} else {
				$error = 0; // OK
			}
			return array('err' => $error, "data" => $query->result_array(), "total" => $total);
		}

		if ($currentPage == '' && $pageSize == '') {
			// 返回全部数据
			$query = $this->db->get('users');
			if (!$query) {
				$error = 1; // ERROR
			} else {
				$error = 0; // OK
			}
			return array('err' => $error, "data" => $query->result_array(), "total" => $total);
		}
		// 返回指定数据
		$offsetRows = $pageSize * ($currentPage - 1); // 数据偏移量
		$query = $this->db->limit($pageSize, $offsetRows)
			->get('users');

		if (!$query) {
			$error = 1; // ERROR
		} else {
			$error = 0; // OK
		}
		return array('err' => $error, "data" => $query->result_array(), "total" => $total);

	}

	// 批量上传
	public function upload($data)
	{
		// 如果学号冲突，则不添加此用户
		$is_exist = $this->db->get_where('users', array('u_number' => $data[4]));
		if ($is_exist->num_rows() > 0) {
			return 0;
		}
		$insert_data = array(
			'u_major' => $data[0],
			'u_name' => $data[1],
			'u_sex' => $data[2],
			'u_class' => $data[3],
			'u_number' => $data[4],
			'u_birthday' => $data[5],
			'u_nation' => $data[6],
			'u_identity' => $data[7],
			'u_password' => sha1($data[4])
		);

		$this->db->insert('users', $insert_data);
		return $this->db->affected_rows();
	}

	// 删除用户
	public function del_user()
	{
		$user_id = $this->input->post_get('u_id', TRUE);
		// 删除多表中的数据
//        $del_tables = array('users', 'helper');
//        $this->db->where('user_id', $user_id);
//        $this->db->delete($del_tables);
//        $result = $this->db->affected_rows();

		$this->db->query("DELETE FROM users WHERE users.u_id={$user_id}");
		$rows = $this->db->affected_rows();

		if ($rows != 0) {
			$error = 0; // OK
		} else {
			$error = 1; // ERROR
		}
		return array('err' => $error, "data" => $rows);
	}
}
