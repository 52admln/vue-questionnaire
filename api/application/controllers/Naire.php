<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Naire extends CI_Controller
{


	public function index()
	{
		$this->load->model('naire_model');

		$result = $this->naire_model->get_naire_list();
		echo json_encode($result);
	}

	// 获取指定问卷详情
	public function detail()
	{
		// todo 获取参数 naire id
		$this->load->model('naire_model');

		$result = $this->naire_model->get_naires();
		echo json_encode($result);
	}

	// 保存问卷
	public function save()
	{
		$this->load->model('naire_model');
		$result = $this->naire_model->save_naire();
		echo json_encode($result);
	}
}
