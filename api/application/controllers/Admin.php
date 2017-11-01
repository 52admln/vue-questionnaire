<?php

class Admin extends CI_Controller
{
	public function login()
	{
		$this->load->model('admin_model');
		$userid = $this->admin_model->login();

		if ($userid != false) {
			$token = jwt_helper::create($userid);
			$error = 0;
			echo json_encode(array('err' => $error, "token" => $token));
		} else {
			$error = 1;
			echo json_encode(array('err' => $error, "message" => "登录失败"));
		}
	}

	public function changepwd()
	{
		$this->load->model('admin_model');
		$header = $this->input->get_request_header('Authorization', TRUE);
		list($token) = sscanf($header, 'token %s');
		if ($header != '' && jwt_helper::validate($token)) {
			$userid = jwt_helper::decode($token)->userId;
			$result = $this->admin_model->changePwd($userid);
			echo json_encode($result);
		} else {
			show_error("Permission denied", 401, "Please check your token.");
		}

	}
}
