<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{

	// 获取所有用户
	public function index()
	{
		$this->load->model('user_model');

		$result = $this->user_model->get_users();

		echo json_encode($result);
	}


	// 批量上传用户
	public function upload()
	{
		$this->load->model('user_model');

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'xls|xlsx';
		$config['max_size'] = 2048;
		$config['max_width'] = 0;
		$config['max_height'] = 0;
		$config['encrypt_name'] = true;

		$this->load->library('upload', $config);

		$err_code = 0;

		if (!$this->upload->do_upload('userfile')) {
			// 上传需要给 读写 权限
			$error = array('error' => $this->upload->display_errors());
			$err_code = 1;
			$msg = '上传文件出错，请重试' . $error;
		} else {
			$data = array('upload_data' => $this->upload->data());
			// 存储上传的信息
			// upload_path + raw_name + file_ext
			$upload_data = $this->upload->data();
			// $this->load->view('welcome_message');

			// 加载PHPExcel
			$this->load->library('PHPExcel.php');
			$this->load->library('PHPExcel/IOFactory.php');

			$uploadfile = './uploads/' . $upload_data['raw_name'] . $upload_data['file_ext'];//获取上传成功的Excel
			$inputFileType = IOFactory::identify($uploadfile);
			$objReader = IOFactory::createReader($inputFileType);//自动识别上传文件类型

			/**  配置单元格数据都以字符串返回  **/
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($uploadfile);//加载目标Excel
			$sheetData = $objPHPExcel->getActiveSheet()->toArray();
			// $sheetData =$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

			$sheet = $objPHPExcel->getSheet(0);//读取第一个sheet
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			$highestColumn = $sheet->getHighestColumn(); // 取得总列数
			$succ_result = $error_result = 0;//设置导入成功和失败的总数为0

			for ($j = 2; $j <= $highestRow; $j++) {
				$strExcel = '';
				for ($k = 'A'; $k <= $highestColumn; $k++) {
					//读取单元格
					if ($k != $highestColumn) {
						$strExcel .= $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue() . ',';
					} else {
						$strExcel .= $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
					}
				}
				$strs = explode(",", $strExcel);
				// var_dump($strs);
				// 往数据库内导入数据
				// 执行数据库语句，返回插入影响行数
				$insert_num = $this->user_model->upload($strs);

				// 如果影响行数大于0，增加1条成功记录
				if ($insert_num > 0) {
					$succ_result += 1;
				} else {
					$error_result += 1;
				}
			}
			unlink('./uploads/' . $upload_data['raw_name'] . $upload_data['file_ext']);
			$msg = "插入成功" . $succ_result . "条数据，插入失败" . $error_result . "条数据。";
		}

		echo json_encode(array("err" => $err_code, "msg" => $msg));
	}

	// 删除用户
	public function del()
	{
		$this->load->model('user_model');

		$result = $this->user_model->del_user();
		echo json_encode($result);

	}

}
