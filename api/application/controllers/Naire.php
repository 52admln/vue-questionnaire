<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Naire extends CI_Controller
{

	// 获取所有用户
	public function detail()
	{
		// todo 获取参数 naire id
		$this->load->model('naire_model');

		$result = $this->naire_model->get_naires();
		echo json_encode($result);
	}

	public function index() {
		$this->load->model('naire_model');

		$result = $this->naire_model->get_naire_list();
		echo json_encode($result);
	}


}
