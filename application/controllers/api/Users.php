<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Users extends REST_Controller {

	private $table = 'm_users';
	private $id_column = 'id_user';
	private $assets = './assets/uploads/';

	function __construct() {
		parent::__construct();
		if (!$this->session->userdata('sicebun_user')) {
			$this->response(null,404);
		}
	}

	function index_get() {
		$draw = (int) $this->get('draw');
		$limit = (int) $this->get('length');
		$offset = (int) $this->get('start');
		$keyword = $this->get('search');
		$order = $this->get('order');

		$or_likes = ['id_user','email','name','address','phone','peran'];
		$recordsTotal = $this->db->count_all($this->table.' m');
		$recordsFiltered = $recordsTotal;
		if(isset($keyword) && $keyword['value'] != '') {
			foreach ($or_likes as $key_or => $or) {
				$this->db->or_like($or, $keyword['value']);
			}
			$recordsFiltered = $this->db->count_all_results($this->table.' m');
		}

		$columns = ['id_user','email','name','address','phone','peran'];
		$this->db->select('*,
			CONCAT("'.$this->assets.'",photo) as photo');
		if(isset($keyword) && $keyword['value'] != '') {
			foreach ($or_likes as $key_or => $or) {
				$this->db->or_like($or, $keyword['value']);
			}
		}
		if(isset($offset) && $limit != '-1') {
			$this->db->limit($limit, $offset);
		} else {
			$this->db->limit(10, 0);
		}
		if(isset($order)) {
			$this->db->order_by($columns[$order[0]['column']],$order[0]['dir']);
		}
		$data = $this->db->get($this->table.' m')->result();
		$this->response((object)array(
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data,
		), 200);
	}

	function one_get(){
		$id = $this->get('id');
		$data = $this->db->where($this->id_column,$id)->get($this->table)->row();
		$this->response($data, 200);
	}

	function save_post(){
		$id = $this->input->post('id');
		$email 					= $this->input->post('email');
		$data['email'] 			= $email;
		$data['name'] 			= $this->input->post('name');
		$data['phone'] 			= $this->input->post('phone');
		$data['peran'] 			= $this->input->post('peran');
		$data['address'] 		= $this->input->post('address');
		$data['is_active'] 		= '1';
		$data['created_date'] 	= Date('Y-m-d H:i:s');
		if (!$id) {
			$data['password'] 	= md5('123456');
		}

		$ganti 		= $this->input->post('ganti');
		$password 	= $this->input->post('password');
		$konfirm 	= $this->input->post('konfirm');

		if (!empty($ganti)) {
			if (strlen($password) < 4) {
				$this->response([
					'status'	=> false,
					'msg'		=> 'Password baru min. 4 karakter',
				], 200);
			}

			if ($password!=$konfirm) {
				$this->response([
					'status'	=> false,
					'msg'		=> 'Password tidak cocok',
				], 200);
			}
		}

		$exist = $this->get_one_by_email($data['email']);
		if ($exist) {
			if ($exist->id_user != $id && $exist->email == $email) {
				$this->response('Email sudah dipakai', 400);
			}
		}

		$this->upload_file_config($this->assets);

		if ($this->upload->do_upload('photo')){
			$uploaded = $this->upload->data();
			$data['photo'] = $uploaded['file_name'];

			$old = $this->get_one($id);
			if ($old) {
				$old_file = $this->assets.$old->photo;
				if (is_file($old_file) && file_exists($old_file)) {
					unlink($old_file);
				}
			}
		}

		$result = false;
		if ($id) {
			if (!empty($ganti)) {
				$data['password'] 	= md5($password);
			}
			$data[$this->id_column] = $id;
			$this->db->where($this->id_column, $id);
			$result = $this->db->update($this->table, $data);
		} else {
			$result = $this->db->insert($this->table, $data);
		}
		if ($result) {
			$this->response([
				'status'	=> $result,
				'msg'		=> 'Berhasil disimpan',
			], 200);
		}else{
			$this->response([
				'status'	=> $result,
				'msg'		=> $this->db->error(),
			], 200);
		}
	}

	function delete_post(){
		$id = $this->input->post('id');
		$old = $this->get_one($id);
		if ($old) {
			$old_photo = $this->assets.$old->photo;
			if (is_file($old_photo) && file_exists($old_photo)) {
				unlink($old_photo);
			}
		}
		$this->db->where($this->id_column, $id);
		$this->db->delete($this->table);
		$this->response(true, 200);
	}

	function activate_post(){
		$id = $this->input->post('id');
		$data['is_active'] = (int) $this->input->post('is_active');
		$this->db->where($this->id_column, $id);
		$this->db->update($this->table, $data);
		$this->response($this->input->post(), 200);
	}

	function get_one($id){
		return $this->db->where($this->id_column,$id)->get($this->table)->row();
	}

	function get_one_by_email($email){
		return $this->db->where('email',$email)->get($this->table)->row();
	}

}