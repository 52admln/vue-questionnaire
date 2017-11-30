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
        // 搜索功能
        $keyword = $this->input->post_get('keyword', TRUE);
        $value = $this->input->post_get('value', TRUE);
        // 如果传入用户ID,返回当前用户的信息
        $currentUser = $this->input->post_get('u_id', TRUE);
        // 参数1: $currentPage 当前页码, 参数2: $pageSize 每页显示条数
        // 如果有参数,则返回分页的数据,没有返回全部数据
        $currentPage = $this->input->post_get('current', TRUE);
        $pageSize = $this->input->post_get('page_size', TRUE);
        // 如果存在搜索条件，则模糊搜索
        if ($value != '' && $keyword != '') {
            $total_query = $this->db->like($keyword, $value)->get('users');
        } else {
            $total_query = $this->db->get('users');
        }
        $total = $total_query->num_rows();

        // 如果传入用户ID,返回当前用户的信息
        if ($currentUser != '') {
            // 返回全部数据
            $query = $this->db->where('u_id', $currentUser)
                ->get('users');
            // todo 搜索条件
            if (!$query) {
                $error = 1; // ERROR
            } else {
                $error = 0; // OK
            }
            return array('err' => $error, "data" => $query->result_array(), "total" => $total);
        }

        if ($currentPage == '' && $pageSize == '') {
            if ($value != '' && $keyword != '') {
                // 搜索条件
                $query = $this->db->like($keyword, $value)->get('users');
            } else {
                // 返回全部数据
                $query = $this->db->get('users');
            }

            if (!$query) {
                $error = 1; // ERROR
            } else {
                $error = 0; // OK
            }
            return array('err' => $error, "data" => $query->result_array(), "total" => $total);
        }
        // 返回指定数据
        $offsetRows = $pageSize * ($currentPage - 1); // 数据偏移量
        if ($value != '' && $keyword != '') {
            $query = $this->db->like($keyword, $value)->limit($pageSize, $offsetRows)
                ->get('users');
        } else {
            $query = $this->db->limit($pageSize, $offsetRows)
                ->get('users');
        }

        if (!$query) {
            $error = 1; // ERROR
        } else {
            $error = 0; // OK
        }
        return array('err' => $error, "data" => $query->result_array(), "total" => $total);

    }

    // 更新用户
    public function update_user()
    {
        $u_id = json_decode($this->input->raw_input_stream, true)['u_id'];
        $update_data = array(
            'u_major' => json_decode($this->input->raw_input_stream, true)['u_major'],
            'u_name' => json_decode($this->input->raw_input_stream, true)['u_name'],
            'u_sex' => json_decode($this->input->raw_input_stream, true)['u_sex'],
            'u_class' => json_decode($this->input->raw_input_stream, true)['u_class'],
            'u_number' => json_decode($this->input->raw_input_stream, true)['u_number'],
            'u_birthday' => json_decode($this->input->raw_input_stream, true)['u_birthday'],
            'u_nation' => json_decode($this->input->raw_input_stream, true)['u_nation'],
            'u_identity' => json_decode($this->input->raw_input_stream, true)['u_identity'],
            'u_password' => sha1(json_decode($this->input->raw_input_stream, true)['u_number'])
        );
        $this->db->where('u_id', $u_id);
        $this->db->update('users', $update_data);
        return $this->db->affected_rows();
    }

    // 批量上传,新增用户
    public function add_user($data)
    {
        // 如果学号冲突，则不添加此用户
        $is_exist = $this->db->get_where('users', array('u_number' => $data[4]));
        if ($is_exist->num_rows() > 0) {
            return 0;
        }
        $insert_data = array(
            'u_major' => $data[0],
            'u_name' => $data[1],
            'u_sex' => $data[2] == '男' ? 0 : 1,
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
        $user_id = json_decode($this->input->raw_input_stream, true)['u_id'];

        // 删除多表中的数据
//        $del_tables = array('users', 'helper');
//        $this->db->where('user_id', $user_id);
//        $this->db->delete($del_tables);
//        $result = $this->db->affected_rows();

        $this->db->query("DELETE FROM users WHERE users.u_id in ({$user_id})");
        $rows = $this->db->affected_rows();

        if ($rows != 0) {
            $error = 0; // OK
        } else {
            $error = 1; // ERROR
        }
        return array('err' => $error, "data" => $rows);
    }

    // 获取用户ID
    public function get_user_id()
    {
        $name = json_decode($this->input->raw_input_stream, true)['name'];
        $identity = json_decode($this->input->raw_input_stream, true)['identity'];
        $nId = json_decode($this->input->raw_input_stream, true)['n_id'];

        $query = $this->db->get_where('users', array('u_name' => $name, 'u_identity' => $identity));
        $row = $query->row_array();
        if (count($row) > 0) {
            // 数据库查找该用户
            $query_naire = $this->db->get_where('result', array('n_id' => $nId, 'u_id' => $row["u_id"]));
            $row_naire = $query_naire->num_rows();
            $data = array(
                "u_id" => $row["u_id"],
                "name" => $row["u_name"],
                "isFinished" => $row_naire > 0
            );

            return array('err' => 0, "data" => $data);
        } else {
            return array('err' => 1, "data" => "用户不存在");
        }
    }

    // 获取班级列表
    public function get_class_list()
    {
        $query = $this->db->query('SELECT u_class FROM users GROUP BY u_class');
        $result = $query->result_array();
        return array('err' => 0, "data" => $result);
    }
}
