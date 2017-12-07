<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Naire extends CI_Controller
{

	public function index()
	{
		$this->load->model('naire_model');
		$header = $this->input->get_request_header('Authorization', TRUE);
		list($token) = sscanf($header, 'token %s');
		if ($header != '' && jwt_helper::validate($token)) {
			$result = $this->naire_model->get_naire_list();
			echo json_encode($result);
		} else {
			show_error("Permission denied", 401, "Please check your token.");
		}
	}

	// 获取指定问卷详情
	public function detail()
	{
		// 获取参数 naire id
		$this->load->model('naire_model');
		$result = $this->naire_model->get_naires();
		echo json_encode($result);
	}

	// 保存问卷
	public function save()
	{
		$this->load->model('naire_model');
		$header = $this->input->get_request_header('Authorization', TRUE);
		list($token) = sscanf($header, 'token %s');
		if ($header != '' && jwt_helper::validate($token)) {
			$result = $this->naire_model->save_naire();
			echo json_encode($result);
		} else {
			show_error("Permission denied", 401, "Please check your token.");
		}

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
		$header = $this->input->get_request_header('Authorization', TRUE);
		list($token) = sscanf($header, 'token %s');
		if ($header != '' && jwt_helper::validate($token)) {
			$result = $this->naire_model->del_naire();
			echo json_encode($result);
		} else {
			show_error("Permission denied", 401, "Please check your token.");
		}

	}

	// 问卷统计
	public function statis()
	{
		$this->load->model('naire_model');
		$header = $this->input->get_request_header('Authorization', TRUE);
		list($token) = sscanf($header, 'token %s');
		if ($header != '' && jwt_helper::validate($token)) {
			$result = $this->naire_model->statis_naire();
			echo json_encode($result);
		} else {
			show_error("Permission denied", 401, "Please check your token.");
		}
	}

	// 交叉分析
	public function crossanalysis()
	{
		$this->load->model('naire_model');
		$header = $this->input->get_request_header('Authorization', TRUE);
		list($token) = sscanf($header, 'token %s');
		if ($header != '' && jwt_helper::validate($token)) {
			$result = $this->naire_model->cross_analysis();
			echo json_encode($result);
		} else {
			show_error("Permission denied", 401, "Please check your token.");
		}
	}

    // 交叉分析题目选项
    public function questions()
    {
        $this->load->model('naire_model');
        $header = $this->input->get_request_header('Authorization', TRUE);
        list($token) = sscanf($header, 'token %s');
        if ($header != '' && jwt_helper::validate($token)) {
            $result = $this->naire_model->get_questions();
            echo json_encode($result);
        } else {
            show_error("Permission denied", 401, "Please check your token.");
        }
    }

	// 样本数据
	public function sourcedata() 
	{
		$this->load->model('naire_model');
		$header = $this->input->get_request_header('Authorization', TRUE);
		list($token) = sscanf($header, 'token %s');
		if ($header != '' && jwt_helper::validate($token)) {
			$result = $this->naire_model->source_data();
			echo json_encode($result);
		} else {
			show_error("Permission denied", 401, "Please check your token.");
		}
	}

	// 查看统计情况
	public function submitStatis()
	{
		$this->load->model('naire_model');
		$header = $this->input->get_request_header('Authorization', TRUE);
		list($token) = sscanf($header, 'token %s');
		if ($header != '' && jwt_helper::validate($token)) {
			$result = $this->naire_model->submit_statis();
			echo json_encode($result);
		} else {
			show_error("Permission denied", 401, "Please check your token.");
		}
	}

	public function changeStatus()
	{
		$this->load->model('naire_model');
		$header = $this->input->get_request_header('Authorization', TRUE);
		list($token) = sscanf($header, 'token %s');
		if ($header != '' && jwt_helper::validate($token)) {
			$result = $this->naire_model->change_status();
			echo json_encode($result);
		} else {
			show_error("Permission denied", 401, "Please check your token.");
		}
	}

	public function changeTime() {
        $this->load->model('naire_model');
        $header = $this->input->get_request_header('Authorization', TRUE);
        list($token) = sscanf($header, 'token %s');
        if ($header != '' && jwt_helper::validate($token)) {
            $result = $this->naire_model->change_time();
            echo json_encode($result);
        } else {
            show_error("Permission denied", 401, "Please check your token.");
        }
    }
}
