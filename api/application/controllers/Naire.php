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

	// 提交问卷
	public function submit()
	{
		$this->load->model('naire_model');
		$result = $this->naire_model->submit_naire();
		echo json_encode($result);
	}

	// 删除问卷
	public function del()
	{
		$this->load->model('naire_model');
		$result = $this->naire_model->del_naire();
		echo json_encode($result);
	}

	public function statis() {
		$this->load->model('naire_model');
		$result = $this->naire_model->statis_naire();
		echo json_encode($result);
	}

	public function crossanalysis() {
		$this->load->model('naire_model');
		$result=$this->naire_model->cross_analysis();
		echo json_encode($result);
	}
}
