<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Dashboard extends REST_Controller {

	function __construct() {
		parent::__construct();
		if (!$this->session->userdata('sicebun_user')) {
			$this->response(null,404);
		}
	}

	function index_get(){
		$table = 'sapi';
		$draw = (int) $this->get('draw');
		$limit = (int) $this->get('length');
		$offset = (int) $this->get('start');
		$keyword = $this->get('search');
		$order = $this->get('order');

		$recordsTotal = $this->db->group_by('status')->count_all_results($table);
		$recordsFiltered = $recordsTotal;
		if(isset($keyword) && $keyword['value'] != '') {
			foreach ($or_likes as $key_or => $or) {
				$this->db->or_like($or, $keyword['value']);
			}
			$this->db->group_by('status');
			$recordsFiltered = $this->db->count_all_results($table);
		}

		$columns = ['id_sapi','status','count(status)'];
		$this->db->select('id_sapi,status,count(status) as total');
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
		$this->db->group_by('status');
		$this->db->order_by('count(status) desc');
		$data = $this->db->get($table)->result();
		$this->response((object)array(
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data,
		), 200);
	}

}