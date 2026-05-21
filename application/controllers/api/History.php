<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class History extends REST_Controller {

	private $table = 'cattle_weighing';
	private $id_column = 'id_weighing';
	private $assets = './assets/uploads/';
	private $assets_thumb = '';

	function __construct() {
		$this->assets = $this->assets.$this->table.'/';
		$this->assets_thumb = $this->assets.'thumb/';
		parent::__construct();
		if (!$this->session->userdata('sicebun_user')) {
			$this->response(null,404);
		}
	}

	function chart_get() {
		$id = $this->get('id');
		$this->db->select('avg(bobot) as value, DATE_FORMAT(cw.created_date, "%Y-%m-%d") as date');
		$this->db->group_by('DATE_FORMAT(cw.created_date, "%Y-%m-%d")');
		$this->db->order_by('cw.created_date','asc');
		$this->db->where('cw.id_cattle',$id);
		$this->db->join('cattle c', 'c.id_cattle=cw.id_cattle','left');
		$result = $this->db->get($this->table.' cw')->result();
		$this->response($result, 200);
	}

	function index_get($id='') {
		$draw = (int) $this->get('draw');
		$limit = (int) $this->get('length');
		$offset = (int) $this->get('start');
		$keyword = $this->get('search');
		$order = $this->get('order');

		$recordsTotal = $this->count_all_results($id);
		$recordsFiltered = $recordsTotal;
		if(isset($keyword) && $keyword['value'] != '') {
			$this->db->or_like('cw.lingkar_dada', $keyword['value']);
			$this->db->or_like('cw.panjang_badan', $keyword['value']);
			$this->db->or_like('cw.bobot', $keyword['value']);
			$this->db->or_like('cw.estimated', $keyword['value']);
			$this->db->or_like('cw.created_date', $keyword['value']);
			$this->db->where('cw.id_cattle',$id);
			$this->db->join('cattle c', 'c.id_cattle=cw.id_cattle','left');
			$recordsFiltered = $this->db->count_all_results($this->table.' cw');
		}

		$columns = array('cw.id_weighing','cw.lingkar_dada','cw.panjang_badan','cw.bobot','cw.estimated','cw.photo','cw.created_date');
		$this->db->select('cw.id_weighing,cw.lingkar_dada,cw.panjang_badan,cw.bobot,cw.estimated,cw.photo,cw.created_date');
		$this->db->join('cattle c', 'c.id_cattle=cw.id_cattle','left');
		if(isset($keyword) && $keyword['value'] != '') {
			$this->db->or_like('cw.lingkar_dada', $keyword['value']);
			$this->db->or_like('cw.panjang_badan', $keyword['value']);
			$this->db->or_like('cw.bobot', $keyword['value']);
			$this->db->or_like('cw.estimated', $keyword['value']);
			$this->db->or_like('cw.created_date', $keyword['value']);
		}
		if(isset($offset) && $limit != '-1') {
			$this->db->limit($limit, $offset);
		} else {
			$this->db->limit(10, 0);
		}
		if(isset($order)) {
			$this->db->order_by($columns[$order[0]['column']],$order[0]['dir']);
		}
		$this->db->where('cw.id_cattle',$id);
		$data = $this->db->get($this->table.' cw')->result();
		$this->response((object)array(
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data,
			'id' => $id,
		), 200);
	}

	function count_all_results($id) {
		return $this->db->where('cw.id_cattle',$id)->join('cattle c', 'c.id_cattle=cw.id_cattle','left')->count_all_results($this->table.' cw');
	}

	function one_get(){
		$id = $this->get('id');
		$data = $this->db->where($this->id_column,$id)->get($this->table)->row();
		$this->response($data, 200);
	}

	function save_post(){
		$id = $this->input->post('id');
		$data['id_cattle'] = $this->input->post('id_cattle');

		$data['lingkar_dada'] = $this->input->post('lingkar_dada');
		$data['panjang_badan'] = $this->input->post('panjang_badan');
		$data['bobot'] = $this->input->post('bobot');
		$data['writed_date'] = Date('Y-m-d H:i:s');

		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$file = substr(str_shuffle($permitted_chars), 0, 11);
		$this->upload_file_config($this->assets, $file);

		if ($this->upload->do_upload('file')){
			$uploaded = $this->upload->data();
			$this->resizeImage($this->assets, $uploaded['file_name']);
			$data['photo'] = $uploaded['file_name'];
			$data['thumb'] = str_replace('.', '_thumb.', $uploaded['file_name']);

			$old = $this->get_one($id);
			if ($old) {
				unlink($this->assets.$old->photo);
				unlink($this->assets_thumb.$old->thumb);
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
			unlink($this->assets.$old->photo);
			unlink($this->assets_thumb.$old->thumb);
		}
		$this->db->where($this->id_column, $id);
		$this->db->delete($this->table);
		$this->response(true, 200);
	}


	function get_one($id){
		return $this->db->where($this->id_column,$id)->get($this->table)->row();
	}

}