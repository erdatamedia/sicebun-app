<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Sapi extends REST_Controller {

	private $table = 'm_sapi';
	private $id_column = 'id_sapi';
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

		$or_likes = ['no_sapi','peternak','alamat','nama_bangsa','tgl_lahir','ib_1','ib_2','ib_3','gangrep','status'];
		$recordsTotal = $this->db->count_all($this->table.' m');
		$recordsFiltered = $recordsTotal;
		if(isset($keyword) && $keyword['value'] != '') {
			foreach ($or_likes as $key_or => $or) {
				$this->db->or_like($or, $keyword['value']);
			}
			$this->db->join('m_bangsa b', 'b.id_bangsa=m.id_bangsa','left');
			$recordsFiltered = $this->db->count_all_results($this->table.' m');
		}


		$columns = ['id_sapi','no_sapi','peternak','alamat','provinsi','tgl_lahir','status','ib_1','ib_2','ib_3','gangrep','status'];
		$this->db->select('id_sapi,no_sapi,peternak,alamat,status,provinsi,kota,kecamatan,kelurahan,
			DATE_FORMAT(m.tgl_lahir, "%d-%m-%Y %H:%i") as tgl_lahir,
			DATE_FORMAT(m.ib_1, "%d-%m-%Y %H:%i") as ib_1,
			DATE_FORMAT(m.ib_2, "%d-%m-%Y %H:%i") as ib_2,
			DATE_FORMAT(m.ib_3, "%d-%m-%Y %H:%i") as ib_3,
			DATE_FORMAT(m.birahi_1, "%d-%m-%Y %H:%i") as birahi_1,
			DATE_FORMAT(m.birahi_2, "%d-%m-%Y %H:%i") as birahi_2,
			DATE_FORMAT(m.birahi_3, "%d-%m-%Y %H:%i") as birahi_3,
			gangrep,straw_1,straw_2,straw_3,
			CONCAT("'.$this->assets.'",foto_pemilik) as foto_pemilik,
			CONCAT("'.$this->assets.'",foto_sapi) as foto_sapi,
			b.nama_bangsa as bangsa');
		$this->db->join('m_bangsa b', 'b.id_bangsa=m.id_bangsa','left');
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
		if ($data) {
			$bangsa = $this->db->where('id_bangsa', $data->id_bangsa)->get('m_bangsa')->row();
			$data->nama_bangsa = $bangsa ? $bangsa->nama_bangsa : '';
		}
		$this->response($data, 200);
	}

	function save_post(){
		$id 					= $this->input->post('id');
		$id_inseminator 		= $this->user->id_user;
		if (!$id) {
			$data['id_inseminator'] = $id_inseminator;
		}
		$no_sapi 				= $this->input->post('no_sapi');
		$data['no_sapi'] 		= $no_sapi;
		$data['peternak'] 		= $this->input->post('peternak');
		$data['alamat'] 		= $this->input->post('alamat');
		$data['provinsi'] 		= $this->input->post('provinsi');
		$data['kota'] 			= $this->input->post('kota');
		$data['kecamatan'] 		= $this->input->post('kecamatan');
		$data['kelurahan'] 		= $this->input->post('kelurahan');
		$data['id_bangsa'] 		= $this->input->post('id_bangsa');
		$data['tgl_lahir'] 		= $this->input->post('tgl_lahir');
		$data['ib_1'] 			= $this->input->post('ib_1');
		$ib_2 					= $this->input->post('ib_2');
		if ($ib_2) {
			$data['ib_2'] 		= $ib_2;
		}
		$ib_3 					= $this->input->post('ib_3');
		if ($ib_3) {
			$data['ib_3'] 		= $ib_3;
		}
		$data['status'] 		= $this->input->post('status');
		$data['gangrep'] 		= $this->input->post('gangrep');
		$data['created_date'] 	= Date('Y-m-d H:i:s');

		$exist = $this->get_one_by_no($no_sapi);
		if ($exist) {
			if ($exist->id_sapi != $id && $exist->no_sapi == $no_sapi && $exist->id_inseminator == $id_inseminator) {
				$this->response('No Sapi sudah ada', 400);
			}
		}

		$this->upload_file_config($this->assets);

		if ($this->upload->do_upload('foto_pemilik')){
			$uploaded = $this->upload->data();
			$data['foto_pemilik'] = $uploaded['file_name'];

			$old = $this->get_one($id);
			if ($old) {
				$old_file = $this->assets.$old->foto_pemilik;
				if (is_file($old_file) && file_exists($old_file)) {
					unlink($old_file);
				}
			}
		}


		if ($this->upload->do_upload('foto_sapi')){
			$uploaded = $this->upload->data();
			$data['foto_sapi'] = $uploaded['file_name'];

			$old = $this->get_one($id);
			if ($old) {
				$old_file = $this->assets.$old->foto_sapi;
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
			$old_foto_pemilik = $this->assets.$old->foto_pemilik;
			if (is_file($old_foto_pemilik) && file_exists($old_foto_pemilik)) {
				unlink($old_foto_pemilik);
			}
			$old_foto_sapi = $this->assets.$old->foto_sapi;
			if (is_file($old_foto_sapi) && file_exists($old_foto_sapi)) {
				unlink($old_foto_sapi);
			}
		}
		$this->db->where($this->id_column, $id);
		$this->db->delete($this->table);
		$this->response(true, 200);
	}

	function bangsa_get() {
		$q = $this->get('q');
		$page = (int) $this->get('page');

		$recordsFiltered = $this->db->count_all('m_bangsa');
		if(isset($q) && $q != '') {
			$this->db->like('nama_bangsa', $q);
			$recordsFiltered = $this->db->count_all_results('m_bangsa');
		}

		$this->db->select('id_bangsa as id,nama_bangsa as name');
		if(isset($q) && $q != '') {
			$this->db->like('nama_bangsa', $q);
		}
		if(isset($page)) {
			$this->db->limit(10, $page*10);
		}
		$this->db->order_by('nama_bangsa','asc');
		$data = $this->db->get('m_bangsa')->result();
		$this->response((object)array(
			'incomplete_results' => false,
			'total_count' => $recordsFiltered,
			'items' => $data,
		), 200);
	}

	function inseminator_get() {
		$q = $this->get('q');
		$page = (int) $this->get('page');

		$recordsFiltered = $this->db->count_all('m_users');
		if(isset($q) && $q != '') {
			$this->db->like('name', $q);
			$recordsFiltered = $this->db->count_all_results('m_users');
		}

		$this->db->select('id_user as id,name as name');
		if(isset($q) && $q != '') {
			$this->db->like('name', $q);
		}
		if(isset($page)) {
			$this->db->limit(10, $page*10);
		}
		$this->db->order_by('name','asc');
		$data = $this->db->get('m_users')->result();
		$this->response((object)array(
			'incomplete_results' => false,
			'total_count' => $recordsFiltered,
			'items' => $data,
		), 200);
	}

	function get_one($id) {
		return $this->db->where($this->id_column,$id)->get($this->table)->row();
	}

	function get_one_by_no($no_sapi) {
		return $this->db->where('no_sapi',$no_sapi)->get($this->table)->row();
	}

}