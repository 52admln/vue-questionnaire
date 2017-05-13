<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Naire extends CI_Controller
{

	// 获取所有用户
	public function index()
	{
		$this->load->model('naire_model');

		$result = $this->naire_model->get_naires();
		echo json_encode($result);
	}



}
