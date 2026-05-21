<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class News extends REST_Controller {

	private $table = 'news';
	private $id_column = 'id_news';
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

		$or_likes = ['n.title'];
		$recordsTotal = $this->db->count_all($this->table);
		$recordsFiltered = $recordsTotal;
		if(isset($keyword) && $keyword['value'] != '') {
			foreach ($or_likes as $key_or => $or) {
				$this->db->or_like($or, $keyword['value']);
			}
			$this->db->join('news_category nc', 'nc.id_category=n.id_category','left');
			$this->db->join('users u', 'u.id_user=n.id_user','left');
			$this->db->where_in('state',['draft','published']);
			$recordsFiltered = $this->db->count_all_results($this->table.' n');
		}

		$columns = ['n.id_news','nc.name','n.title','u.fullname','n.click_count','n.state','n.cover'];
		$this->db->select(
			'n.id_news,nc.name as category,n.title,u.fullname as author,n.click_count,n.state,
			CONCAT("'.base_url($this->assets).'",n.cover) as cover');
		$this->db->join('news_category nc', 'nc.id_category=n.id_category','left');
		$this->db->join('users u', 'u.id_user=n.id_user','left');
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
		$this->db->where_in('state',['draft','published']);
		$data = $this->db->get($this->table.' n')->result();
		$this->response((object)[
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data,
		], 200);
	}

	function one_get(){
		$id = $this->get('id');
		$this->db->select('n.*,concat("'.base_url($this->assets_thumb).'",n.thumb) as url, nc.name as category,u.fullname as author');
		$this->db->join('news_category nc', 'nc.id_category=n.id_category','left');
		$this->db->join('users u', 'u.id_user=n.id_user','left');
		$data = $this->db->where($this->id_column,$id)->get($this->table.' n')->row();
		$data->size = filesize($this->assets.$data->cover);
		$this->response($data, 200);
	}

	function save_post(){
		$id = $this->input->post('id');

		$data['id_category'] = $this->input->post('id_category');
		$data['id_user'] = $this->input->post('id_user');
		$data['title'] = $this->input->post('title');
		$data['tags'] = $this->input->post('tags');
		$data['subtitle'] = $this->input->post('subtitle');
		$data['content'] = $this->input->post('content');

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
				$old_file = $this->assets.$old->cover;
				$old_file_thumb = $this->assets_thumb.$old->thumb;
				if (is_file($old_file) && file_exists($old_file)) {
					unlink($old_file);
				}
				if (is_file($old_file_thumb) && file_exists($old_file_thumb)) {
					unlink($old_file_thumb);
				}
			}
		}

		if ($id) {
			$data[$this->id_column] = $id;
			$this->db->where($this->id_column, $id);
			$result = $this->db->update($this->table, $data);
			if ($result) {
				$this->response($data, 200);
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

	function draft_post(){
		$id = $this->input->post('id');
		$data['state'] = 'draft';
		$this->db->where($this->id_column, $id);
		$this->db->update($this->table, $data);
		$this->response(true, 200);
	}

	function publish_post(){
		$id = $this->input->post('id');
		$data['state'] = 'published';
		$data['posted_date'] = date('Y-m-d H:i:s');
		$this->db->where($this->id_column, $id);
		$this->db->update($this->table, $data);
		$this->response(true, 200);
	}

	function delete_post(){
		$id = $this->input->post('id');
		$old = $this->get_one($id);
		if ($old) {
			$old_file = $this->assets.$old->cover;
			$old_file_thumb = $this->assets_thumb.$old->thumb;
			if (is_file($old_file) && file_exists($old_file)) {
				unlink($old_file);
			}
			if (is_file($old_file_thumb) && file_exists($old_file_thumb)) {
				unlink($old_file_thumb);
			}
		}
		$data['state'] = 'deleted';
		$this->db->where($this->id_column, $id);
		$this->db->update($this->table, $data);
		// $this->db->delete($this->table);
		$this->response(true, 200);
	}

	function delete_image_post(){
		$id = $this->input->post('id');
		$old = $this->get_one($id);
		if ($old) {
			$old_file = $this->assets.$old->cover;
			$old_file_thumb = $this->assets_thumb.$old->thumb;
			if (is_file($old_file) && file_exists($old_file)) {
				unlink($old_file);
			}
			if (is_file($old_file_thumb) && file_exists($old_file_thumb)) {
				unlink($old_file_thumb);
			}
		}
		$data['cover'] = '';
		$data['thumb'] = '';
		$this->db->where($this->id_column, $id);
		$this->db->update($this->table, $data);
		$this->response(true, 200);
	}

	function category_get() {
		$q = $this->get('q');
		$page = (int) $this->get('page');

		$recordsFiltered = $this->db->count_all('news_category');
		if(isset($q) && $q != '') {
			$this->db->like('name', $q);
			$recordsFiltered = $this->db->count_all_results('news_category');
		}

		$this->db->select('id_category as id,name as name');
		if(isset($q) && $q != '') {
			$this->db->like('name', $q);
		}
		if(isset($page)) {
			$this->db->limit(10, $page*10);
		}
		$this->db->order_by('name','asc');
		$data = $this->db->get('news_category')->result();
		$this->response((object)array(
			'incomplete_results' => false,
			'total_count' => $recordsFiltered,
			'items' => $data,
		), 200);
	}

	function author_get() {
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