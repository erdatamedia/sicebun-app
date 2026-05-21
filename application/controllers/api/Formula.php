<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Formula extends REST_Controller {

	private $table = 'formula';
	private $id_column = 'id_formula';

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

		$recordsTotal = $this->db->count_all($this->table);
		$recordsFiltered = $recordsTotal;
		if(isset($keyword) && $keyword['value'] != '') {
			$this->db->or_like('f.formula', $keyword['value']);
			$this->db->or_like('f.gender', $keyword['value']);
			$this->db->or_like('f.fisiologis', $keyword['value']);
			$this->db->or_like('cr.race_name', $keyword['value']);
			$this->db->join('cattle_race cr', 'cr.id_race=f.id_race','left');
			$recordsFiltered = $this->db->count_all_results($this->table.' f');
		}

		$columns = ['f.id_formula','f.formula','cr.race_name','f.gender','f.fisiologis'];
		$this->db->select('f.id_formula,f.formula,f.id_race,cr.race_name,f.gender,f.fisiologis');
		$this->db->join('cattle_race cr', 'cr.id_race=f.id_race','left');
		if(isset($keyword) && $keyword['value'] != '') {
			$this->db->or_like('f.formula', $keyword['value']);
			$this->db->or_like('f.gender', $keyword['value']);
			$this->db->or_like('f.fisiologis', $keyword['value']);
			$this->db->or_like('cr.race_name', $keyword['value']);
		}
		if(isset($offset) && $limit != '-1') {
			$this->db->limit($limit, $offset);
		} else {
			$this->db->limit(10, 0);
		}
		if(isset($order)) {
			$this->db->order_by($columns[$order[0]['column']],$order[0]['dir']);
		}
		$data = $this->db->get($this->table.' f')->result();
		$this->response((object)array(
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data,
		), 200);
	}

	function one_get(){
		$id = $this->get('id');
		$this->db->select('f.id_formula,f.formula,f.id_race,cr.race_name,f.gender,f.fisiologis');
		$this->db->join('cattle_race cr', 'cr.id_race=f.id_race','left');
		$data = $this->db->where($this->id_column,$id)->get($this->table.' f')->row();
		if ($data){
			$filtered 	= array_filter(explode(' ', $data->formula), function ($var) { return (stripos($var, '$') !== false); });
			$parameter = array_values($filtered);
			$data->parameter = array_unique($parameter);
		}
		$this->response($data, 200);
	}

	function save_post(){
		$id = $this->input->post('id');

		$formula = $this->input->post('formula');
		$data['formula'] = $formula;

		$id_race = $this->input->post('race');
		if ($id_race!='') {
			$data['id_race'] = $id_race;
		}
		$gender = $this->input->post('gender');
		if ($gender!='') {
			$data['gender'] = $gender;
		}
		$fisiologis = $this->input->post('fisiologis');
		if ($fisiologis!='') {
			$data['fisiologis'] = $fisiologis;
		}

		$invalid = '';
		$formula_arr 	= explode(' ', $formula);
		if (!in_array('$lingkar_dada', $formula_arr)) {
			$invalid .= 'Sertakan variabel $lingkar_dada';
		}

		if (!in_array('$panjang_badan', $formula_arr)) {
			if ($invalid!='') {
				$invalid .= ' dan $panjang_badan';
			} else {
				$invalid .= 'Sertakan variabel $panjang_badan';
			}
		}

		if ($invalid!='') {
			$this->response($invalid, 400);
		}

		$exist = $this->getDataByCiri($id_race, $gender, $fisiologis);
		if ($exist) {
			if ($exist->id_formula!=$id) {
				$this->response('Rumus dengan formula tersebut sudah ada (ID '.$exist->id_formula.'), sesuaikan lagi ciri2nya', 400);
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

	function delete_post(){
		$id = $this->input->post('id');
		$this->db->where($this->id_column, $id);
		$this->db->delete($this->table);
		$this->response(true, 200);
	}

	function calculate_post(){
		$formula = $this->input->post('formula');
		$data = $this->input->post();

		unset($data['formula']);
		unset($data['0']);
		foreach ($data as $key => $value) {
			$formula = str_replace($key, $value, $formula);
		}

		$result = eval('return '. $formula .';');
		$result = round($result, 2);

		$this->response(array(
			'formula' => $formula, 
			'result' => $result, 
		), 200);
	}

	function race_get() {
		$q = $this->get('q');
		$page = (int) $this->get('page');

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


	function get_one($id){
		return $this->db->where($this->id_column,$id)->get($this->table)->row();
	}

	function getDataByCiri($id_race, $gender , $fisiologis) {
		if ($id_race!='') {
			$this->db->where('id_race', $id_race);
		} else {
			$this->db->where('id_race IS NULL', null, false);
		}

		if ($gender!='') {
			$this->db->where('gender', $gender);
		} else {
			$this->db->where('gender IS NULL', null, false);
		}

		if ($fisiologis!='') {
			$this->db->where('fisiologis', $fisiologis);
		} else {
			$this->db->where('fisiologis IS NULL', null, false);
		}

		return $this->db->get($this->table)->row();
	}

}