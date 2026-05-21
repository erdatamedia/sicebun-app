<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Bangsa extends REST_Controller {

	private $table = 'm_bangsa';
	private $id_column = 'id_bangsa';
	private $assets = './assets/uploads/';
	private $assets_thumb = '';
	private $user;

	function __construct() {
		$this->assets_thumb = $this->assets.'thumb/';
		parent::__construct();
		if (!$this->session->userdata('sicebun_user')) {
			$this->response(null,404);
		}
		$this->user = $this->session->userdata('sicebun_user');
	}

	function index_get() {
		$draw = (int) $this->get('draw');
		$limit = (int) $this->get('length');
		$offset = (int) $this->get('start');
		$keyword = $this->get('search');
		$order = $this->get('order');

		$or_likes = ['nama_bangsa'];
		$recordsTotal = $this->db->count_all($this->table.' m');
		$recordsFiltered = $recordsTotal;
		if(isset($keyword) && $keyword['value'] != '') {
			foreach ($or_likes as $key_or => $or) {
				$this->db->or_like($or, $keyword['value']);
			}
			$recordsFiltered = $this->db->count_all_results($this->table.' m');
		}


		$columns = ['id_bangsa','nama_bangsa'];
		$this->db->select('id_bangsa,nama_bangsa');
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
		$this->response((object)[
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data,
		], 200);
	}

	function one_get(){
		$id = $this->get('id');
		$data = $this->db->where($this->id_column,$id)->get($this->table)->row();
		$this->response($data, 200);
	}

	function save_post(){
		$id 					= $this->input->post('id');

		$nama_bangsa 				= $this->input->post('nama_bangsa');
		$data['nama_bangsa'] 		= $nama_bangsa;

		$exist = $this->getBangsa($nama_bangsa);
		if ($exist) {
			if ($exist->id_bangsa != $id && $exist->nama_bangsa == $nama_bangsa) {
				$this->response('Nama bangsa sudah ada', 400);
			}
		}

		$result = false;
		if ($id) {
			$data[$this->id_column] = $id;
			$this->db->where($this->id_column, $id);
			$result = $this->db->update($this->table, $data);
		} else {
			$result = $this->db->insert($this->table, $data);
			$id = $this->db->insert_id();
		}

		if ($result) {
			$this->response($result, 200);
		}else{
			$this->response($this->db->error(), 400);
		}
	}

	function delete_post(){
		$id = $this->input->post('id');

		$this->db->where($this->id_column, $id);
		$this->db->delete($this->table);
		$this->response(true, 200);
	}

	function get_one($id) {
		return $this->db->where($this->id_column,$id)->get($this->table)->row();
	}

	function getBangsa($nama_bangsa) {
		return $this->db->where('nama_bangsa',$nama_bangsa)->get($this->table)->row();
	}

}