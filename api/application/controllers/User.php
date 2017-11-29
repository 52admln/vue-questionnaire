<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{

    // 获取所有用户
    public function index()
    {
        $this->load->model('user_model');
        $header = $this->input->get_request_header('Authorization', TRUE);
        list($token) = sscanf($header, 'token %s');
        if ($header != '' && jwt_helper::validate($token)) {
            $result = $this->user_model->get_users();
            echo json_encode($result);
        } else {
            show_error("Permission denied", 401, "Please check your token.");
        }
    }

    // 批量上传用户
    public function upload()
    {
        $this->load->model('user_model');
        // todo 自适应服务器环境路径
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
                $insert_num = $this->user_model->add_user($strs);

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
        $header = $this->input->get_request_header('Authorization', TRUE);
        list($token) = sscanf($header, 'token %s');
        if ($header != '' && jwt_helper::validate($token)) {
            $result = $this->user_model->del_user();
            echo json_encode($result);
        } else {
            show_error("Permission denied", 401, "Please check your token.");
        }

    }

    // 获取用户id
    public function getId()
    {
        $this->load->model('user_model');
        $result = $this->user_model->get_user_id();
        echo json_encode($result);
    }

    // 获取班级列表
    public function getClass()
    {
        $this->load->model('user_model');
        $header = $this->input->get_request_header('Authorization', TRUE);
        list($token) = sscanf($header, 'token %s');
        if ($header != '' && jwt_helper::validate($token)) {
            $result = $this->user_model->get_class_list();
            echo json_encode($result);
        } else {
            show_error("Permission denied", 401, "Please check your token.");
        }
    }

    // 新增用户
    public function addUser()
    {
        $this->load->model('user_model');
        $header = $this->input->get_request_header('Authorization', TRUE);
        list($token) = sscanf($header, 'token %s');
        if ($header != '' && jwt_helper::validate($token)) {
            $insert_data = array(
                0 => json_decode($this->input->raw_input_stream, true)['u_major'],
                1 => json_decode($this->input->raw_input_stream, true)['u_name'],
                2 => json_decode($this->input->raw_input_stream, true)['u_sex'],
                3 => json_decode($this->input->raw_input_stream, true)['u_class'],
                4 => json_decode($this->input->raw_input_stream, true)['u_number'],
                5 => json_decode($this->input->raw_input_stream, true)['u_birthday'],
                6 => json_decode($this->input->raw_input_stream, true)['u_nation'],
                7 => json_decode($this->input->raw_input_stream, true)['u_identity'],
                8 => json_decode($this->input->raw_input_stream, true)['u_number']
            );
            if ($this->user_model->add_user($insert_data) > 0) {
                $result = array('err' => 0, "data" => '新增 1 个用户成功');
            } else {
                $result = array('err' => 1, "data" => '新增用户失败，此用户已存在');
            }
            echo json_encode($result);
        } else {
            show_error("Permission denied", 401, "Please check your token.");
        }
    }

    // 更新用户
    public function updateUser()
    {
        $this->load->model('user_model');
        $header = $this->input->get_request_header('Authorization', TRUE);
        list($token) = sscanf($header, 'token %s');
        if ($header != '' && jwt_helper::validate($token)) {
            if ($this->user_model->update_user() > 0) {
                $result = array('err' => 0, "data" => '修改用户成功');
            } else {
                $result = array('err' => 1, "data" => '修改用户失败');
            }
            echo json_encode($result);
        } else {
            show_error("Permission denied", 401, "Please check your token.");
        }
    }

}
