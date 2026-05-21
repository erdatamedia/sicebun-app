<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Cattle extends REST_Controller {

	private $table = 'cattle';
	private $id_column = 'id_cattle';
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

	function index_get() {
		$draw = (int) $this->get('draw');
		$limit = (int) $this->get('length');
		$offset = (int) $this->get('start');
		$keyword = $this->get('search');
		$order = $this->get('order');

		$or_likes = ['c.eartag','c.birthdate','c.gender','cr.race_name','u.fullname'];
		$recordsTotal = $this->db->count_all($this->table);
		$recordsFiltered = $recordsTotal;
		if(isset($keyword) && $keyword['value'] != '') {
			foreach ($or_likes as $key_or => $or) {
				$this->db->or_like($or, $keyword['value']);
			}
			$this->db->join('cattle_race cr', 'cr.id_race=c.id_race','left');
			$this->db->join('users u', 'u.id_user=c.id_user','left');
			$recordsFiltered = $this->db->count_all_results($this->table.' c');
		}

		$columns = array('c.id_cattle','c.eartag','cr.race_name','u.fullname','birthdate','gender','c.id_cattle');
		$this->db->select('c.id_cattle,c.eartag,cr.race_name,u.fullname as owner,DATE_FORMAT(c.birthdate, "%d-%m-%Y %h:%m") as birthdate ,c.gender,DATEDIFF(CURRENT_DATE, c.birthdate)/365 AS age,DATEDIFF(CURRENT_DATE, c.birthdate) AS age_in_days');
		$this->db->join('cattle_race cr', 'cr.id_race=c.id_race','left');
		$this->db->join('users u', 'u.id_user=c.id_user','left');
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
		$data = $this->db->get($this->table.' c')->result();
		$this->response((object)array(
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data,
		), 200);
	}

	function one_get(){
		$id = $this->get('id');
		$this->db->select('c.*,cr.race_name,u.fullname as owner');
		$this->db->join('cattle_race cr', 'cr.id_race=c.id_race','left');
		$this->db->join('users u', 'u.id_user=c.id_user','left');
		$data = $this->db->where($this->id_column,$id)->get($this->table.' c')->row();
		$this->response($data, 200);
	}

	function save_post(){
		$id = $this->input->post('id');

		$data['eartag'] = $this->input->post('eartag');
		$data['id_race'] = $this->input->post('race');
		$data['id_user'] = $this->input->post('user');
		$data['birthdate'] = $this->input->post('birthdate');
		$data['gender'] = $this->input->post('gender');

		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$file = substr(str_shuffle($permitted_chars), 0, 11);
		$this->upload_file_config($this->assets, $file);

		if ($this->upload->do_upload('file')){
			$uploaded = $this->upload->data();
			$this->resizeImage($this->assets, $uploaded['file_name']);
			$data['cover'] = $uploaded['file_name'];
			$data['thumb'] = str_replace('.', '_thumb.', $uploaded['file_name']);

			$old = $this->get_one($id);
			if ($old) {
				unlink($this->assets.$old->cover);
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
			unlink($this->assets.$old->cover);
			unlink($this->assets_thumb.$old->thumb);
		}
		$this->db->where($this->id_column, $id);
		$this->db->delete($this->table);
		$this->response(true, 200);
	}

	function race_get() {
		$q = $this->get('q');
		$page = (int) $this->get('page');

		$recordsFiltered = $this->db->count_all('cattle_race');
		if(isset($q) && $q != '') {
			$this->db->like('race_name', $q);
			$recordsFiltered = $this->db->count_all_results('cattle_race');
		}

		$this->db->select('id_race as id,race_name as name');
		if(isset($q) && $q != '') {
			$this->db->like('race_name', $q);
		}
		if(isset($page)) {
			$this->db->limit(10, $page*10);
		}
		$this->db->order_by('race_name','asc');
		$data = $this->db->get('cattle_race')->result();
		$this->response((object)array(
			'incomplete_results' => false,
			'total_count' => $recordsFiltered,
			'items' => $data,
		), 200);
	}

	function user_get() {
		$q = $this->get('q');
		$page = (int) $this->get('page');

		$recordsFiltered = $this->db->count_all('users');
		if(isset($q) && $q != '') {
			$this->db->or_like('fullname', $q);
			$recordsFiltered = $this->db->count_all_results('users');
		}

		$this->db->select('id_user as id,fullname as name');
		if(isset($q) && $q != '') {
			$this->db->or_like('fullname', $q);
		}
		if(isset($page) && $page > 1) {
			$this->db->limit(10, ($page*10)-10);
		}
		$this->db->order_by('fullname','asc');
		$data = $this->db->get('users')->result();
		$this->response((object)array(
			'incomplete_results' =>  false,
			'total_count' => $recordsFiltered,
			'items' => $data,
		), 200);
	}

	function get_one($id){
		return $this->db->where($this->id_column,$id)->get($this->table)->row();
	}

}