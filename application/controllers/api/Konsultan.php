<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Konsultan extends REST_Controller {

	private $table = 'm_konsultan';
	private $id_column = 'id_konsultan';
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

		$or_likes = ['nama','telp','asal','alamat','keahlian','profesi'];
		$recordsTotal = $this->db->count_all($this->table.' m');
		$recordsFiltered = $recordsTotal;
		if(isset($keyword) && $keyword['value'] != '') {
			foreach ($or_likes as $key_or => $or) {
				$this->db->or_like($or, $keyword['value']);
			}
			$recordsFiltered = $this->db->count_all_results($this->table.' m');
		}


		$columns = ['id_konsultan','nama','telp','asal','alamat','link','keahlian','profesi'];
		$this->db->select('id_konsultan,nama,telp,asal,alamat,link,keahlian,profesi,is_active,
			CONCAT("'.$this->assets.'",foto) as foto,
			');
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

		$data['nama'] 		= $this->input->post('nama');
		$data['telp'] 		= $this->input->post('telp');
		$data['asal'] 		= $this->input->post('asal');
		$data['alamat'] 	= $this->input->post('alamat');
		$data['link'] 		= $this->input->post('link');
		$data['profesi'] 	= $this->input->post('profesi');
		$data['keahlian'] 	= $this->input->post('keahlian');

		$this->upload_file_config($this->assets);

		if ($this->upload->do_upload('foto')){
			$uploaded = $this->upload->data();
			$data['foto'] = $uploaded['file_name'];

			$old = $this->get_one($id);
			if ($old) {
				$old_file = $this->assets.$old->foto;
				if (is_file($old_file) && file_exists($old_file)) {
					unlink($old_file);
				}
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
		$old = $this->get_one($id);
		if ($old) {
			$foto = $this->assets.$old->foto_pemilik;
			if (is_file($foto) && file_exists($foto)) {
				unlink($foto);
			}
		}
		$this->db->where($this->id_column, $id);
		$this->db->delete($this->table);
		$this->response(true, 200);
	}

	function get_one($id) {
		return $this->db->where($this->id_column,$id)->get($this->table)->row();
	}

	function getBangsa($nama) {
		return $this->db->where('nama',$nama)->get($this->table)->row();
	}

	function activate_post(){
		$id = $this->input->post('id');
		$data['is_active'] = (int) $this->input->post('is_active');
		$this->db->where($this->id_column, $id);
		$this->db->update($this->table, $data);
		$this->response($this->input->post(), 200);
	}

}