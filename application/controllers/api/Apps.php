<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Apps extends REST_Controller {

	private $assets = 'assets/uploads/';
	private $assets_thumb = '';
	private $cattle_asset = '';
	private $cattle_asset_thumb = '';
	private $weighing_asset = '';
	private $weighing_asset_thumb = '';
	private $news_asset = '';
	private $news_asset_thumb = '';
	private $market_asset = '';
	private $market_asset_thumb = '';

	function __construct() {
		parent::__construct();
		$this->cattle_asset 		= $this->assets.'cattle/';
		$this->cattle_asset_thumb 	= $this->cattle_asset.'thumb/';
		$this->weighing_asset 		= $this->assets.'weighing/';
		$this->weighing_asset_thumb = $this->weighing_asset.'thumb/';
		$this->news_asset 			= $this->assets.'news/';
		$this->news_asset_thumb 	= $this->news_asset.'thumb/';
		$this->market_asset 		= $this->assets.'market/';
		$this->market_asset_thumb 	= $this->market_asset.'thumb/';
	}

	function index_get() {
		$p = eval('return 2+10;');
		print $p;
	}

	function cattle_get() {
		$email = $this->get('email');

		$this->db->select('c.id_cattle,c.eartag,cr.race_name as race,u.fullname as owner,DATE_FORMAT(c.birthdate, "%d-%m-%Y %h:%m") as birthdate ,c.gender,ROUND(DATEDIFF(CURRENT_DATE, c.birthdate)/365*12) AS age,DATEDIFF(CURRENT_DATE, c.birthdate) AS age_in_days,
			concat("'.base_url($this->cattle_asset).'",c.cover) as cover,
			concat("'.base_url($this->cattle_asset_thumb).'",c.thumb) as thumb,
			');
		$this->db->join('cattle_race cr', 'cr.id_race=c.id_race','left');
		$this->db->join('users u', 'u.id_user=c.id_user','left');
		$this->db->where('u.email', $email);
		$result = $this->db->get('cattle c')->result();
		foreach ($result as $key => $value) {
			$result[$key]->PBBH = $this->getPBBH($value->id_cattle);
		}
		$this->response($result,200);
	}


	function cattle_post() {
		$id							= $this->input->post('id_cattle');
		$email						= $this->input->post('email');
		$data['eartag']				= $this->input->post('eartag');
		$data['birthdate']			= $this->input->post('birthdate');
		$data['gender']				= $this->input->post('gender');
		$data['id_race']			= $this->input->post('id_race');

		$data = array_filter($data);

		$status 	= false;
		$msg 		= 'Gagal Menyimpan Data';
		$result		= null;

		$data['id_user']			= $this->getUser($email)->id_user;

		if ($id) {
			$this->db->where('id_cattle', $id);
			$data['updated_date'] = date('Y-m-d H:i:s');
			$this->db->update('cattle',$data);
		} else {
			$this->db->insert('cattle',$data);
			$id = $this->db->insert_id();
		}

		if ($this->db->affected_rows()>0) {
			$msg 		= 'Berhasil Menyimpan Data';
			$status 	= true;
		}

		$this->response([],200);
	}
	
	function delete_cattle_post() {
		$id_cattle = $this->input->post('id_cattle');

		$this->db->where('id_cattle', $id_cattle);
		$this->db->delete('cattle');
		
		$result = null;
		if ($this->db->affected_rows()>0) {
			$result = $this->db->get('cattle')->result();
		}
		$this->response($result,200);
	}

	function history_get() {
		$id_cattle = $this->get('id_cattle');

		$this->db->select('cw.id_weighing,cw.id_cattle,cw.lingkar_dada,cw.panjang_badan,cw.bobot,cw.estimated,
			concat("'.base_url($this->weighing_asset).'",cw.photo) as photo,
			DATE_FORMAT(cw.created_date, "%d-%m-%Y") as created_date');
		$this->db->join('cattle c', 'c.id_cattle=cw.id_cattle','left');
		$this->db->where('cw.id_cattle',$id_cattle);
		$this->db->order_by('cw.created_date');
		$result = $this->db->get('cattle_weighing cw')->result();
		$this->response($result,200);
	}

	function history_post() {
		$newdata['id_cattle'] 	= $this->input->post('id_cattle');
		$newdata['bobot']		= $this->input->post('bobot');
		$created_date			= $this->input->post('created_date');

		if (!is_null($created_date) && $created_date!='Pilih Tanggal Input') {
			$newdata['created_date'] = $created_date;
		}

		$status 	= false;
		$msg 		= 'Gagal disimpan';
		$result		= null;

		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$file = substr(str_shuffle($permitted_chars), 0, 11);
		$this->upload_file_config($this->weighing_asset, $file);

		if ($this->upload->do_upload('photo')){
			$uploaded = $this->upload->data();
			$this->resizeImage($this->weighing_asset, $uploaded['file_name']);
			$newdata['photo'] = $uploaded['file_name'];
		}

		$this->db->insert('cattle_weighing', $newdata);
		if ($this->db->affected_rows()>0) {
			$msg 		= 'Berhasil Menyimpan Data';
			$status 	= true;
		}

		$this->response([
			'status'	=> $status,
			'msg'		=> $msg,
			'data'		=> $result,
		], 200);
	}

	function history2_post() {
		$newdata['id_cattle'] 	= $this->input->post('id_cattle');
		$newdata['bobot']		= $this->input->post('bobot');
		$newdata['estimated']	= $this->input->post('estimated');
		$created_date			= $this->input->post('created_date');

		if (!is_null($created_date) && $created_date!='Pilih Tanggal Input') {
			$newdata['created_date'] = $created_date;
		}

		$status 	= false;
		$msg 		= 'Gagal disimpan';
		$result		= null;

		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$file = substr(str_shuffle($permitted_chars), 0, 11);
		$this->upload_file_config($this->weighing_asset, $file);

		if ($this->upload->do_upload('photo')){
			$uploaded = $this->upload->data();
			$this->resizeImage($this->weighing_asset, $uploaded['file_name']);
			$newdata['photo'] = $uploaded['file_name'];
		}

		$this->db->insert('cattle_weighing', $newdata);
		if ($this->db->affected_rows()>0) {
			$msg 		= 'Berhasil Menyimpan Data';
			$status 	= true;
		}

		$this->response([
			'status'	=> $status,
			'msg'		=> $msg,
			'data'		=> $result,
		], 200);
	}

	function delete_history_post() {
		$id_weighing = $this->input->post('id_weighing');

		$this->db->where('id_weighing', $id_weighing);
		$this->db->delete('cattle_weighing');
		
		$result = false;
		if ($this->db->affected_rows()>0) {
			$result = true;
		}
		$this->response($result,200);
	}

	function formula_post() {
		$panjang_badan 	= $this->input->post('panjang_badan');
		$lingkar_dada 	= $this->input->post('lingkar_dada');
		$id_race 		= $this->input->post('id_race');
		$fisiologis 	= $this->input->post('fisiologis');
		$gender 		= $this->input->post('gender');


		if ($panjang_badan=='' || $lingkar_dada=='') {
			$this->response([
				'status'	=> false,
				'msg'		=> 'Panjang badan dan Lingkar dada wajib diisi',
				'data'		=> null,
			], 400);
		} 

		if ($id_race!='') {
			$this->db->where('id_race',$id_race);
		}

		if ($fisiologis!='') {
			$this->db->where('fisiologis',$fisiologis);
		}

		if ($gender!='') {
			$this->db->where('gender',$gender);
		}

		$formulaFound = $this->db->get('formula')->row();
		if ($formulaFound) {
			$formula = $formulaFound->formula;
			$formula = str_replace('$panjang_badan', $panjang_badan, $formula);
			$formula = str_replace('$lingkar_dada', $lingkar_dada, $formula);
			$result = eval('return '.$formula.';');
			$result = round($result, 2);
			$this->response([
				'status'	=> true,
				'msg'		=> 'Berhasil dihitung',
				'data'		=> $result,
			], 200);
		} else {
			$defaultFormula = $this->calculate($lingkar_dada, $panjang_badan);
			if ($defaultFormula) {
				$this->response([
					'status'	=> true,
					'msg'		=> 'Berhasil dihitung',
					'data'		=> $defaultFormula,
				], 200);
			} else {
				$this->response([
					'status'	=> false,
					'msg'		=> 'Rumus tidak ditemukan',
					'data'		=> $defaultFormula,
				], 200);
			}
		}
	}

	function race_get(){
		$result = $this->db->get('cattle_race')->result();
		$this->response($result,200);
	}

	function news_get() {
		$this->db->select(
			'n.id_news,nc.name as category,n.title,fullname as author,n.click_count,n.state,
			concat("'.base_url($this->news_asset).'",n.cover) as cover,
			concat("'.base_url($this->news_asset_thumb).'",n.thumb) as thumb,
			n.content,n.subtitle,n.posted_date');
		$this->db->join('news_category nc', 'nc.id_category=n.id_category','left');
		$this->db->join('users u', 'u.id_user=n.id_user','left');
		$this->db->where('n.state','published');
		$result = $this->db->get('news n')->result();
		$this->response($result,200);
	}

	function market_get() {
		$name = $this->get('name');
		$type = $this->get('type');

		$this->db->select('m.*,
			group_concat("'.base_url($this->market_asset).'",mp.file) as photos');
		$this->db->join('market_photo mp', 'mp.id_market=m.id_market','left');
		$this->db->group_by('m.id_market');
		if ($name!='') {
			$this->db->or_like('name', $name);
		}
		if ($type!='') {
			$type = explode(',', $type);
			$this->db->where_in('type', $type);
		}
		$result = $this->db->get('market m')->result();
		foreach ($result as $key => $value) {
			$photos = $this->getPhoto($value->id_market);
			$result[$key]->photos = $photos;
			$result[$key]->cover = (count($photos) > 0 ? $photos[0]->file : '' );
		}
		$this->response($result,200);
	}

	function getPhoto($id_market){
		return $this->db->select('*,
			file as name,
			thumb as name_thumb,
			concat("'.base_url($this->market_asset).'",file) as file,
			concat("'.base_url($this->market_asset_thumb).'",thumb) as thumb,
			')
		->where('id_market', $id_market)->get('market_photo')->result();
	}

	function getUser($email){
		return $this->db->where('email', $email)->get('users')->row();
	}

	function getCattles($email){
		$this->db->select('c.id_cattle,c.eartag,cr.race_name as race,u.fullname as owner,DATE_FORMAT(c.birthdate, "%d-%m-%Y %h:%m") as birthdate ,c.gender,ROUND(DATEDIFF(CURRENT_DATE, c.birthdate)/365) AS age,DATEDIFF(CURRENT_DATE, c.birthdate) AS age_in_days,
			concat("'.base_url($this->cattle_asset).'",c.cover) as cover,
			concat("'.base_url($this->cattle_asset_thumb).'",c.thumb) as thumb,
			');
		$this->db->join('cattle_race cr', 'cr.id_race=c.id_race','left');
		$this->db->join('users u', 'u.id_user=c.id_user','left');
		$this->db->where('u.email', $email);
		return $this->db->get('cattle c')->result();
	}

	function getPBBH($id_cattle){
		$first 	= $this->db->where('id_cattle',$id_cattle)->order_by('id_weighing','ASC')->limit(1)->get('cattle_weighing cw1')->row();
		$last 	= $this->db->where('id_cattle',$id_cattle)->order_by('id_weighing','DESC')->limit(1)->get('cattle_weighing cw2')->row();
		$count 	= $this->db->where('id_cattle',$id_cattle)->count_all_results('cattle_weighing cw3');
		$date1  = $first ? date_create($first->created_date) : 0;
		$date2  = $last ? date_create($last->created_date) : 0;
		$diff   = $first && $last ? date_diff($date1,$date2) : 0;
		$count  = $diff ? $diff->days : 0;

		$bobotAkhir = $last ? $last->bobot : 0;
		$bobotAwal = $first ?  $first->bobot : 0;
		if ($count>0) {
			return round(($bobotAkhir - $bobotAwal) / ($count), 2);    
		} else {
			return 0.0;
		}
		
	}

	function calculate($lingkar_dada, $panjang_badan) {
		$this->db->where('id_race IS NULL', null, false);
		$this->db->where('gender IS NULL', null, false);
		$this->db->where('fisiologis IS NULL', null, false);
		$formula = $this->db->get('formula')->row();
		if ($formula) {
			$formula = $formula->formula;
			$formula = str_replace('$panjang_badan', $panjang_badan, $formula);
			$formula = str_replace('$lingkar_dada', $lingkar_dada, $formula);
			$result = eval('return '.$formula.';');
			return round($result, 2);
		} else {
			return false;
		}
	}

}