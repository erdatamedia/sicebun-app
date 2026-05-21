<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Direktori extends REST_Controller {

	private $table = 'm_direktori';
	private $id_column = 'id_direktori';
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

		$recordsTotal = $this->db->count_all($this->table.' m1');
		$recordsFiltered = $recordsTotal;
		if(isset($keyword) && $keyword['value'] != '') {
			$this->db->like('nama', $keyword['value']);
			$recordsFiltered = $this->db->count_all_results($this->table.' m1');
		}

		$columns = ['m1.id_direktori','m1.nama','COUNT(m2.id_galeri)'];
		$this->db->select('m1.*,COUNT(m2.id_galeri) as total,
			CONCAT("'.$this->assets.'",cover) as cover');
		$this->db->join('m_galeri m2','m2.id_direktori=m1.id_direktori','left');
		if(isset($keyword) && $keyword['value'] != '') {
			$this->db->like('nama', $keyword['value']);
		}
		if(isset($offset) && $limit != '-1') {
			$this->db->limit($limit, $offset);
		} else {
			$this->db->limit(10, 0);
		}
		if(isset($order)) {
			$this->db->order_by($columns[$order[0]['column']],$order[0]['dir']);
		}
		$this->db->group_by('m1.id_direktori');
		$data = $this->db->get($this->table.' m1')->result();
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
		$data->foto = $data ? 
		$this->db->select('*, file as filename, CONCAT("'.$this->assets.'",file) as file')
		->where($this->id_column,$data->id_direktori)->get('m_galeri')->result() : ""; 
		$this->response($data, 200);
	}

	function save_post(){
		$id 				= $this->input->post('id');
		$data['nama'] 		= $this->input->post('nama');
		$data['informasi'] 	= $this->input->post('informasi');

		$this->upload_file_config($this->assets);

		if ($this->upload->do_upload('cover')){
			$uploaded = $this->upload->data();
			$data['cover'] = $uploaded['file_name'];

			$old = $this->get_one($id);
			if ($old) {
				$old_file = $this->assets.$old->cover;
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
		}
		if ($result) {
			$this->response($result, 200);
		}else{
			$this->response($this->db->error(), 400);
		}
	}

	function save_foto_post(){
		$id 				= $this->input->post('id');

		$this->upload_file_config($this->assets);

		$files = array();
		if ($_FILES){
			foreach ($_FILES['file']['name'] as $key => $filename) {

				$_FILES['upload']['name']= $_FILES['file']['name'][$key];
				$_FILES['upload']['type']= $_FILES['file']['type'][$key];
				$_FILES['upload']['tmp_name']= $_FILES['file']['tmp_name'][$key];
				$_FILES['upload']['error']= $_FILES['file']['error'][$key];
				$_FILES['upload']['size']= $_FILES['file']['size'][$key];

				if ($this->upload->do_upload('upload')) {
					$uploaded = $this->upload->data();
					$result = $this->add_photo($id, $uploaded['file_name'], $uploaded['file_type']);
				}
			}
		}

		$this->response($result, 200);
	}

	function delete_post(){
		$id = $this->input->post('id');
		$old = $this->get_one($id);
		if ($old) {
			$old_medias = $this->get_medias($id);
			foreach ($old_medias as $key => $old_media) {
				$file_old_media = $this->assets.$old_media->file;
				if (is_file($file_old_media) && file_exists($file_old_media)) {
					unlink($file_old_media);
				}
			}
			$this->clear_medias($id);
			$old_cover = $this->assets.$old->cover;
			if (is_file($old_cover) && file_exists($old_cover)) {
				unlink($old_cover);
			}
		}
		$this->db->where($this->id_column, $id);
		$this->db->delete($this->table);
		$this->response(true, 200);
	}

	function get_one($id){
		return $this->db->where($this->id_column,$id)->get($this->table)->row();
	}

	function get_one_media($id){
		return $this->db->where('id_galeri',$id)->get('m_galeri')->row();
	}

	function get_medias($id){
		return $this->db->where('id_direktori',$id)->get('m_galeri')->result();
	}

	function clear_medias($id){
		return $this->db->where('id_direktori',$id)->delete('m_galeri');
	}

	function add_photo($id_direktori, $file, $type){
		$data['id_direktori'] 	= $id_direktori;
		$data['file'] 			= $file;
		$data['type'] 			= $type;
		$data['created_date'] 	= Date('Y-m-d H:i:s');
		return $this->db->insert('m_galeri', $data);
	}

	function delete_media_post(){
		$id = $this->input->post('id');
		$old = $this->get_one_media($id);
		if ($old) {
			$old_file = $this->assets.$old->file;
			if (is_file($old_file) && file_exists($old_file)) {
				unlink($old_file);
			}

		}
		$this->db->where('id_galeri', $id);
		$this->db->delete('m_galeri');
		$this->response(true, 200);
	}

}