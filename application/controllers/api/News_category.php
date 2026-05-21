<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class News_category extends REST_Controller {

	private $table = 'news_category';
	private $id_column = 'id_category';

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

		$recordsTotal = $this->db->count_all($this->table.' nc');
		$recordsFiltered = $recordsTotal;
		if(isset($keyword) && $keyword['value'] != '') {
			$this->db->like('name', $keyword['value']);
			$this->db->join('news n', 'n.id_category=nc.id_category','left');
			$recordsFiltered = $this->db->count_all_results($this->table.' nc');
		}

		$columns = ['id_category','name'];
		$this->db->select('nc.id_category,nc.name,COUNT(n.id_news) as total_news');
		$this->db->join('news n', 'n.id_category=nc.id_category','left');
		if(isset($keyword) && $keyword['value'] != '') {
			$this->db->like('name', $keyword['value']);
		}
		if(isset($offset) && $limit != '-1') {
			$this->db->limit($limit, $offset);
		} else {
			$this->db->limit(10, 0);
		}
		if(isset($order)) {
			$this->db->order_by($columns[$order[0]['column']],$order[0]['dir']);
		}
		$this->db->group_by('nc.id_category');
		$data = $this->db->get($this->table.' nc')->result();
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
		$id = $this->input->post('id');
		$data['name'] = $this->input->post('name');
		
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$file = substr(str_shuffle($permitted_chars), 0, 11);
		$this->upload_file_config('./assets/uploads/', $file);

		if ($this->upload->do_upload('file')){
			$uploaded = $this->upload->data();
			$this->resizeImage('./assets/uploads/', $uploaded['file_name']);
			$data['file'] = $uploaded['file_name'];
			$data['thumb'] = str_replace('.', '_thumb.', $uploaded['file_name']);

			$old = $this->get_one($id);
			if ($old) {
				unlink('./assets/uploads/'.$old->file);
				unlink('./assets/uploads/thumb/'.$old->thumb);
			}
		}

		if ($id) {
			$data[$this->id_column] = $id;
			$this->db->where($this->id_column, $id);
			$result = $this->db->update($this->table, $data);
			if ($result) {
				$this->response($result, 200);
			}else{
				$this->response($this->db->error(), 400);
			}
		} else {
			$result = $this->db->insert($this->table, $data);
			if ($result) {
				$this->response($result, 200);
			}else{
				$this->response($this->db->error(), 400);
			}
		}
	}

	function delete_post(){
		$id = $this->input->post('id');
		$data = $this->get_one($id);
		if ($data) {
			unlink('./assets/uploads/'.$data->file);
			unlink('./assets/uploads/thumb/'.$data->thumb);
		}
		$this->db->where($this->id_column, $id);
		$this->db->delete($this->table);
		$this->response(true, 200);
	}

	function get_one($id){
		return $this->db->where($this->id_column,$id)->get($this->table)->row();
	}

}