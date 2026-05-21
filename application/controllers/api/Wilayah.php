<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Wilayah extends REST_Controller {

	function __construct() {
		parent::__construct();
	}


	function index_post() {
		$limit = 20;
		$keyword 	= $this->input->post('keyword');
		$offset 	= $this->input->post('offset');

		$table 		= $this->input->post('table');
		$ref 		= $this->input->post('ref');

		if ($table=='m_kota' && !empty($ref)) {
			$ref_id = $this->getRef('m_provinsi', $ref);
			$this->db->where('id_provinsi', $ref_id);
		}

		if ($table=='m_kecamatan' && !empty($ref)) {
			$ref_id = $this->getRef('m_kota', $ref);
			$this->db->where('id_kota', $ref_id);
		}

		if ($table=='m_kelurahan' && !empty($ref)) {
			$ref_id = $this->getRef('m_kecamatan', $ref);
			$this->db->where('id_kecamatan', $ref_id);
		}

		if ($keyword) {
			$this->db->like('nama', $keyword);
		}

		$this->db->limit($limit);
		$this->db->offset($offset);
		$this->db->order_by('nama', 'asc');

		$result = $this->db->get($table)->result();
		$total = $this->getTotal();
		$totalPage = ceil($total/$limit);

		$this->response([
			'status'		=> $result ? true : false,
			'msg'			=> $result ? count($result)." data ditemukan" : "Kosong",
			'data'			=> $result ? $result : [],
			'total'			=> $total ? $total : 0,
			'totalPage'		=> $totalPage,
			'limit'			=> $limit,
		], 200);
	}

	function provinsi_get() {
		$q = $this->get('q');
		$page = (int) $this->get('page');

		$recordsFiltered = $this->db->count_all('m_provinsi');
		if(isset($q) && $q != '') {
			$this->db->like('nama', $q);
			$recordsFiltered = $this->db->count_all_results('m_provinsi');
		}

		$this->db->select('nama as id,nama as name');
		if(isset($q) && $q != '') {
			$this->db->like('nama', $q);
		}
		if(isset($page)) {
			$this->db->limit(10, $page*10);
		}
		$this->db->order_by('nama','asc');
		$data = $this->db->get('m_provinsi')->result();
		$this->response((object)array(
			'incomplete_results' => false,
			'total_count' => $recordsFiltered,
			'items' => $data,
		), 200);
	}

	function kota_get() {
		$q = $this->get('q');
		$page = (int) $this->get('page');

		$recordsFiltered = $this->db->count_all('m_kota');
		if(isset($q) && $q != '') {
			$this->db->like('nama', $q);
			$recordsFiltered = $this->db->count_all_results('m_kota');
		}

		$this->db->select('nama as id,nama as name');
		if(isset($q) && $q != '') {
			$this->db->like('nama', $q);
		}
		if(isset($page)) {
			$this->db->limit(10, $page*10);
		}
		$this->db->order_by('nama','asc');
		$data = $this->db->get('m_kota')->result();
		$this->response((object)array(
			'incomplete_results' => false,
			'total_count' => $recordsFiltered,
			'items' => $data,
		), 200);
	}

	function kecamatan_get() {
		$q = $this->get('q');
		$page = (int) $this->get('page');

		$recordsFiltered = $this->db->count_all('m_kecamatan');
		if(isset($q) && $q != '') {
			$this->db->like('nama', $q);
			$recordsFiltered = $this->db->count_all_results('m_kecamatan');
		}

		$this->db->select('nama as id,nama as name');
		if(isset($q) && $q != '') {
			$this->db->like('nama', $q);
		}
		if(isset($page)) {
			$this->db->limit(10, $page*10);
		}
		$this->db->order_by('nama','asc');
		$data = $this->db->get('m_kecamatan')->result();
		$this->response((object)array(
			'incomplete_results' => false,
			'total_count' => $recordsFiltered,
			'items' => $data,
		), 200);
	}

	function kelurahan_get() {
		$q = $this->get('q');
		$page = (int) $this->get('page');

		$recordsFiltered = $this->db->count_all('m_kelurahan');
		if(isset($q) && $q != '') {
			$this->db->like('nama', $q);
			$recordsFiltered = $this->db->count_all_results('m_kelurahan');
		}

		$this->db->select('nama as id,nama as name');
		if(isset($q) && $q != '') {
			$this->db->like('nama', $q);
		}
		if(isset($page)) {
			$this->db->limit(10, $page*10);
		}
		$this->db->order_by('nama','asc');
		$data = $this->db->get('m_kelurahan')->result();
		$this->response((object)array(
			'incomplete_results' => false,
			'total_count' => $recordsFiltered,
			'items' => $data,
		), 200);
	}

	function getRef($ref, $nama) {
		$refFound = $this->db->where('nama', $nama)->get($ref)->row();
		return $refFound ? $refFound->id : '';
	}


	function getTotal() {
		$keyword 	= $this->input->post('keyword');
		$table 		= $this->input->post('table');
		$ref 		= $this->input->post('ref');

		if ($table=='m_kota' && !empty($ref)) {
			$ref_id = $this->getRef('m_provinsi', $ref);
			$this->db->where('id_provinsi', $ref_id);
		}

		if ($table=='m_kecamatan' && !empty($ref)) {
			$ref_id = $this->getRef('m_kota', $ref);
			$this->db->where('id_kota', $ref_id);
		}

		if ($table=='m_kelurahan' && !empty($ref)) {
			$ref_id = $this->getRef('m_kecamatan', $ref);
			$this->db->where('id_kecamatan', $ref_id);
		}

		if ($keyword) {
			$this->db->like('nama', $keyword);
		}

		return $this->db->get($table)->num_rows();

	}


}