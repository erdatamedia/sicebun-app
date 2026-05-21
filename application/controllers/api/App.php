<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class App extends REST_Controller {

	private $assets = './assets/uploads/';

	function __construct() {
		parent::__construct();
	}


	function login_post() {
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$this->db->select('id_user,email,name,address,phone,peran,
			CONCAT("'.base_url($this->assets).'",photo) as photo');
		$this->db->where('is_active', '1');
		// $this->db->where('peran', 'inseminator');
		$this->db->where('email', $email);
		$this->db->where('password', md5($password));
		$result = $this->db->get('m_users')->row();

		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? "Login Berhasil" : "Akun tidak ditemukan",
			'data'		=> $result ? $result : null,
		], 200);
	}


	function register_post() {
		$data['name'] = $this->input->post('name');
		$data['address'] = $this->input->post('address');
		$data['phone'] = $this->input->post('phone');
		$data['email'] = $this->input->post('email');
		$data['password'] = md5($this->input->post('password'));
		$data['photo'] = 'default.png';
		$data['peran'] = 'inseminator';
		$data['is_active'] = '1';
		$data['created_date'] = Date('Y-m-d H:i:s');

		$exist = $this->getOne('email', $data['email'], 'm_users');
		if ($exist) {
			$this->response([
				'status'	=> false,
				'msg'		=> "Email sudah dipakai",
				'data'		=> null,
			], 200);
		}

		$this->upload_file_config($this->assets);
		if ($this->upload->do_upload('photo')) {
			$uploaded = $this->upload->data();
			$data['photo'] 	= $uploaded['file_name'];
		}
		$result = $this->db->insert('m_users', $data);

		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? "Pendaftaran Berhasil" : "Pendaftaran Gagal",
			'data'		=> $result ? $result : null,
		], 200);
	}


	function update_user_post() {
		$id = $this->input->post('id');
		$data['name'] = $this->input->post('name');
		$data['address'] = $this->input->post('address');
		$data['phone'] = $this->input->post('phone');
		$data['email'] = $this->input->post('email');

		$input['Nama'] = $data['name'];
		$input['Alamat'] = $data['address'];
		$input['No. HP / WA'] = $data['phone'];
		$input['Email'] = $data['email'];
		foreach ($input as $key => $d) {
			if (!$input[$key]) {
				$this->response([
					'status'	=> false,
					'msg'		=> "Isi " . $key,
					'data'		=> null,
				], 200);
			}
		}

		$exist = $this->getOne('email', $data['email'], 'm_users');
		if ($exist && $exist->id_user != $id) {
			$this->response([
				'status'	=> false,
				'msg'		=> "Email sudah dipakai",
				'data'		=> null,
			], 200);
		}

		$this->db->where('id_user', $id);
		$updated = $this->db->update('m_users', $data);
		$result = $updated ? $this->db->select('id_user,email,name,address,phone,peran,CONCAT("'.base_url($this->assets).'",photo) as photo')
		->where('id_user', $id)->get('m_users')->row() : false;

		$this->response([
			'status'	=> $updated ? true : false,
			'msg'		=> $updated ? 'Berhasil diperbarui' : 'Gagal diperbarui',
			'data'		=> $updated ? $result : null,
		], 200);
	}


	function change_password_post() {
		$id = $this->input->post('id');
		$old = $this->input->post('lama');
		$new = $this->input->post('baru');
		$confirm = $this->input->post('konfirm');

		if (strlen($new) < 4) {
			$this->response([
				'status'	=> false,
				'msg'		=> 'Password baru min. 4 karakter',
				'data'		=> null,
			], 200);
		}

		if ($new!=$confirm) {
			$this->response([
				'status'	=> false,
				'msg'		=> 'Password tidak cocok',
				'data'		=> null,
			], 200);
		}

		$result = $id ? $this->db->where('id_user', $id)->get('m_users')->row() : false;
		if (!$result) {
			$this->response([
				'status'	=> false,
				'msg'		=> 'Pengguna tidak ditemukan',
				'data'		=> null,
			], 200);
		} else {
			if ($result->password!=md5($old)) {
				$this->response([
					'status'	=> false,
					'msg'		=> 'Password lama salah',
					'data'		=> null,
				], 200);
			}
			$this->db->where('id_user', $result->id_user);
			$result = $this->db->update('m_users', [
				'password' => md5($new)
			]);
		}

		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? 'Berhasil diganti' : 'Gagal diganti',
			'data'		=> $result ? $result : null,
		], 200);
	}


	function change_foto_user_post() {
		$id = $this->input->post('id');

		$result = $id ? $this->db->select('id_user,email,name,address,phone,peran,CONCAT("'.base_url($this->assets).'",photo) as photo')
		->where('id_user', $id)->get('m_users')->row() : false;
		if (!$result) {
			$this->response([
				'status'	=> false,
				'msg'		=> 'Pengguna tidak ditemukan',
				'data'		=> null,
			], 200);
		} else {

			$this->upload_file_config($this->assets);
			if ($this->upload->do_upload('foto')) {
				$uploaded = $this->upload->data();
				$data['photo'] 	= $uploaded['file_name'];
			} else {
				$this->response([
					'status'	=> false,
					'msg'		=> 'Gagal upload foto',
					'data'		=> null,
				], 200);
			}

			$this->db->where('id_user', $id);
			$updated = $this->db->update('m_users', $data);
			$result = $updated ? $this->db->select('id_user,email,name,address,phone,peran,CONCAT("'.base_url($this->assets).'",photo) as photo')
			->where('id_user', $id)->get('m_users')->row() : false;
		}

		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? 'Berhasil diperbarui' : 'Gagal diperbarui',
			'data'		=> $result ? $result : null,
		], 200);
	}

	function dashboard_post() {
		$id_user = $this->input->post('id_user');
		$status = $this->get_enum_field('m_sapi', 'status');


		$jumlah_sapi = 0;
		$result = (object)[
			'jumlah_sapi' => $jumlah_sapi,
			'status_sapi' => [],
			'direktori' => [],
			'bangsa' => [],
			'konsultan' => [],
			'provinsi' => [],
		];
		foreach ($status as $key => $s) {
			$title = '';
			if ($s=='ib1') {
				$title = 'Inseminasi Buatan 1';
			} else if ($s=='ib2') {
				$title = 'Inseminasi Buatan 2';
			} else if ($s=='ib3') {
				$title = 'Inseminasi Buatan 3';
			} else if ($s=='bunting') {
				$title = 'Bunting';
			} else if ($s=='tidak_bunting') {
				$title = 'Tidak Bunting';
			} else if ($s=='gangrep') {
				$title = 'Gangguan Reproduksi';
			} else if ($s=='kelahiran') {
				$title = 'Kelahiran';
			}

			$initial = '';
			foreach (explode(' ', $title) as $k2 => $t) {
				$initial .= $t[0];
			}


			$jumlah = $this->db
			->where('id_inseminator', $id_user)
			->where('status', $s)
			->count_all_results('m_sapi');
			array_push($result->status_sapi, (object)[
				'initial' => $initial,
				'nama' => $title,
				'jumlah' => $jumlah.' ekor',
				'sapi' => [],
			]);
			$jumlah_sapi += $jumlah;

		}
		$result->jumlah_sapi = $jumlah_sapi.'';
		$result->direktori = $this->db->select('*,CONCAT("'.base_url($this->assets).'",cover) as cover')->get('m_direktori')->result();
		$result->bangsa = $this->db->get('m_bangsa')->result();
		$result->konsultan = $this->db->select('*,CONCAT("'.base_url($this->assets).'",foto) as foto')->limit(5)->get('m_konsultan')->result();
		$result->provinsi = [];

		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? "Berhasil" : "",
			'data'		=> $result ? $result : [],
		], 200);
	}

	function sapi_post() {
		$id_user = $this->input->post('id_user');
		$status = $this->input->post('status');

		$this->db->select('m.*,m.ib_1 as first_ib,b.nama_bangsa as bangsa,
			CONCAT("'.base_url($this->assets).'",m.foto_pemilik) as foto_pemilik,
			CONCAT("'.base_url($this->assets).'",m.foto_sapi) as foto_sapi');
		$this->db->join('m_bangsa b', 'b.id_bangsa=m.id_bangsa','left');
		if ($status) {
			$this->db->where('m.status', $status);
		}
		$this->db->where('m.id_inseminator', $id_user);
		$result = $this->db->get('m_sapi m')->result();

		$this->response([
			'status'	=> $result ? true : true,
			'msg'		=> $result ? 'Berhasil' : '',
			'data'		=> $result ? $result : [],
		], 200);
	}

	function add_sapi_post() {
		$data['id_inseminator'] = $this->input->post('id_inseminator');
		$data['no_sapi'] = $this->input->post('no_sapi');
		$data['peternak'] = $this->input->post('peternak');
		$data['alamat'] = $this->input->post('alamat');
		$data['provinsi'] = $this->input->post('provinsi');
		$data['kota'] = $this->input->post('kota');
		$data['kecamatan'] = $this->input->post('kecamatan');
		$data['kelurahan'] = $this->input->post('kelurahan');
		$bangsa = $this->input->post('bangsa');

		$bangsaFound = $this->db->where('nama_bangsa', $bangsa)->get('m_bangsa')->row();
		if ($bangsaFound) {
			$data['id_bangsa'] = $bangsaFound->id_bangsa;
		}

		$data['tgl_lahir'] = $this->input->post('tgl_lahir');
		$data['birahi_1'] = $this->input->post('birahi_1');
		$data['ib_1'] = $this->input->post('ib_1');
		$data['straw_1'] = $this->input->post('straw');
		$data['status'] = 'ib1';
		$data['created_date'] = Date('Y-m-d H:i:s');

		$this->upload_file_config($this->assets);

		if ($this->upload->do_upload('foto_pemilik')){
			$uploaded = $this->upload->data();
			$data['foto_pemilik'] = $uploaded['file_name'];
		}

		if ($this->upload->do_upload('foto_sapi')){
			$uploaded = $this->upload->data();
			$data['foto_sapi'] = $uploaded['file_name'];
		}

		$result = $this->db->insert('m_sapi', $data);

		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? "Berhasil ditambahkan" : "Gagal menambahkan",
			'data'		=> $result ? $result : null,
		], 200);
	}

	function get_sapi_post() {
		$id_sapi = $this->input->post('id_sapi');
		$no_sapi = $this->input->post('no_sapi');
		$id_user = $this->input->post('id_user');

		$this->db->select('m.*,b.nama_bangsa as bangsa,
			DATE_FORMAT(m.ib_1, "%d-%m-%Y %H:%i") as first_ib,
			DATE_FORMAT(m.birahi_1, "%d-%m-%Y %H:%i") as first_birahi,
			DATE_FORMAT(m.tgl_lahir, "%Y") as tgl_lahir,
			DATE_FORMAT(m.tgl_melahirkan, "%d-%m-%Y %H:%i") as tgl_melahirkan,
			DATE_FORMAT(m.tgl_bunting, "%d-%m-%Y %H:%i") as tgl_bunting,
			CONCAT("'.base_url($this->assets).'",m.foto_pemilik) as foto_pemilik,
			CONCAT("'.base_url($this->assets).'",m.foto_sapi) as foto_sapi');
		$this->db->join('m_bangsa b', 'b.id_bangsa=m.id_bangsa','left');
		if ($id_sapi!='') {
			$this->db->where('id_sapi', $id_sapi);
		}
		if ($no_sapi!='') {
			$this->db->where('no_sapi', $no_sapi);
			$this->db->where('id_inseminator', $id_user);
		}
		$result = $this->db->get('m_sapi m')->row();
		if ($result) {

			$tgl_ib = ($result->ib_3) ? $result->ib_3 : 
			(($result->ib_2) ? $result->ib_2 : $result->ib_1);

			$today = new DateTime();

			if ($result->ib_1) {
				$birahi_1 = new DateTime($result->birahi_1);
				$ib_1 = new DateTime($result->ib_1);
				$birahi1 = $birahi_1->format('d M Y H:i');
				$ib1 = '( '.$today->diff($ib_1)->format("%a").' hari ) '.$ib_1->format('d M Y H:i');
				$result->ib_1 = $ib1."\n".$result->straw_1."\n".$birahi1;
			}

			if ($result->ib_2) {
				$birahi_2 = new DateTime($result->birahi_2);
				$ib_2 = new DateTime($result->ib_2);
				$birahi2 = $birahi_2->format('d M Y H:i');
				$ib2 = '( '.$today->diff($ib_2)->format("%a").' hari ) '.$ib_2->format('d M Y H:i');
				$result->ib_2 = $ib2."\n".$result->straw_2."\n".$birahi2;
			}

			if ($result->ib_3) {
				$birahi_3 = new DateTime($result->birahi_3);
				$ib_3 = new DateTime($result->ib_3);
				$birahi3 = $birahi_3->format('d M Y H:i');
				$ib3 = ' ( '.$today->diff($ib_3)->format("%a").' hari ) '.$ib_3->format('d M Y H:i');
				$result->ib_3 = $ib3."\n".$result->straw_3."\n".$birahi3;
			}

			if ($tgl_ib && $result->is_bunting=='1') {
				$tgl_ib = new DateTime($tgl_ib);
				$result->umur_kebuntingan = $today->diff($tgl_ib)->format("%a").' hari';
				$tgl_ib->modify('+283 day');
				$start_prediksi = $tgl_ib->format('d M Y');
				$tgl_ib->modify('+3 day');
				$end_prediksi = $tgl_ib->format('d M Y');
				$result->prediksi_tgl_lahir = $start_prediksi."-\n".$end_prediksi;
			}

			if ($result->tgl_melahirkan) {
				$tgl_melahirkan = new DateTime($result->tgl_melahirkan);
				$result->tgl_melahirkan = $tgl_melahirkan->format('d M Y H:i');
				$tgl_melahirkan->modify('+59 day');
				$start_calving = $tgl_melahirkan->format('d M Y');
				$tgl_melahirkan->modify('+2 day');
				$end_calving = $tgl_melahirkan->format('d M Y');
				$result->ib_calving = $start_calving."-\n".$end_calving;
			}
		}
		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? "Berhasil ditemukan" : "Tidak ditemukan",
			'data'		=> $result ? $result : null,
		], 200);
	}

	function update_sapi_post() {
		$id_sapi 			= $this->input->post('id_sapi');
		$data['id_sapi'] 	= $id_sapi;
		$status 			= $this->input->post('status');
		$data['status'] 	= $status;
		$tgl_birahi 		= $this->input->post('tgl_birahi');
		$tgl_ib 			= $this->input->post('tgl_ib');
		$ket 				= $this->input->post('ket');
		$straw 				= $this->input->post('straw');
		$tgl_melahirkan 	= $this->input->post('tgl_melahirkan');

		if ($status=='ib1') {
			$data['birahi_1'] 	= $tgl_birahi;
			$data['ib_1'] 		= $tgl_ib;
			$data['straw_1'] 	= $straw;
			$data['ket_1'] 		= $ket;
		} else if ($status=='ib2') {
			$data['birahi_2'] 	= $tgl_birahi;
			$data['ib_2'] 		= $tgl_ib;
			$data['straw_2'] 	= $straw;
			$data['ket_2'] 		= $ket;
		} else if ($status=='ib3') {
			$data['birahi_3'] 	= $tgl_birahi;
			$data['ib_3'] 		= $tgl_ib;
			$data['straw_3'] 	= $straw;
			$data['ket_3'] 		= $ket;
		} else if ($status=='bunting') {
			$data['is_bunting'] 	= '1';
			$data['bunting'] 		= $ket;
		} else if ($status=='tidak_bunting') {
			$data['is_bunting'] 	= '0';
			$data['tidak_bunting'] 	= $ket;
		} else if ($status=='gangrep') {
			$data['is_bunting'] 	= '0';
			$data['is_melahirkan'] 	= '0';
			$data['gangrep'] 		= $ket;
		} else if ($status=='kelahiran') {
			$data['is_melahirkan'] 	= '1';
			$data['tgl_melahirkan'] = $tgl_melahirkan;
			$data['kelahiran'] 		= $ket;
		} else {
			$data['is_bunting'] 	= '0';
			$data['is_melahirkan'] 	= '0';
		}


		$result = $this->db->where('id_sapi', $id_sapi)->update('m_sapi', $data);
		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? "Berhasil diperbarui" : "Gagal diperbarui",
			'data'		=> $result ? $result : null,
		], 200);
	}

	function change_foto_sapi_post() {
		$id = $this->input->post('id');
		$column = $this->input->post('column');

		$result = $id ? $this->db->where('id_sapi', $id)->get('m_sapi')->row() : false;
		if (!$result) {
			$this->response([
				'status'	=> false,
				'msg'		=> 'Sapi tidak ditemukan',
				'data'		=> null,
			], 200);
		} else {

			$this->upload_file_config($this->assets);
			if ($this->upload->do_upload('foto')) {
				$uploaded = $this->upload->data();
				$data[$column] 	= $uploaded['file_name'];
			} else {
				$this->response([
					'status'	=> false,
					'msg'		=> 'Gagal upload foto',
					'data'		=> null,
				], 200);
			}

			$this->db->where('id_sapi', $id);
			$updated = $this->db->update('m_sapi', $data);
			$result = $updated ? $this->db->where('id_sapi', $id)->get('m_sapi')->row() : false;
		}

		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? 'Berhasil diperbarui' : 'Gagal diperbarui',
			'data'		=> $result ? $result : null,
		], 200);
	}

	function delete_sapi_post() {
		$id_sapi = $this->input->post('id_sapi');

		$old = $this->db->where('id_sapi', $id_sapi)->get('m_sapi')->row();
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

		$result = $this->db->where('id_sapi', $id_sapi)->delete('m_sapi');
		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? "Berhasil dihapus" : "Gagal dihapus",
			'data'		=> $result ? $result : null,
		], 200);
	}


	function status_sapi_post() {
		$id_user = $this->input->post('id_user');
		$status = $this->get_enum_field('m_sapi', 'status');

		$result = [];
		foreach ($status as $key => $s) {
			$title = '';
			if ($s=='ib1') {
				$title = 'Inseminasi Buatan 1';
			} else if ($s=='ib2') {
				$title = 'Inseminasi Buatan 2';
			} else if ($s=='ib3') {
				$title = 'Inseminasi Buatan 3';
			} else if ($s=='bunting') {
				$title = 'Bunting';
			} else if ($s=='tidak_bunting') {
				$title = 'Tidak Bunting';
			} else if ($s=='kelahiran') {
				$title = 'Kelahiran';
			} else if ($s=='gangrep') {
				$title = 'Gangguan Reproduksi';
			}

			$initial = '';
			foreach (explode(' ', $title) as $k2 => $t) {
				$initial .= $t[0];
			}

			$this->db->where('id_inseminator', $id_user);
			$this->db->where('status', $s);
			$jumlah = $this->db->count_all_results('m_sapi');
			array_push($result, (object)[
				'initial' => $initial,
				'nama' => $title,
				'jumlah' => $jumlah.' ekor',
			]);
		}

		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? "Berhasil" : "",
			'data'		=> $result ? $result : [],
		], 200);
	}

	function bangsa_get() {
		$result = $this->db->get('m_bangsa')->result();
		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? "Berhasil" : "",
			'data'		=> $result ? $result : [],
		], 200);
	}

	function direktori_get() {
		$this->db->select('*,CONCAT("'.$this->assets.'",cover) as cover');
		$result = $this->db->get('m_direktori')->result();

		foreach ($result as $key => $value) {
			$total = $this->db->where('id_direktori', $value->id_direktori)->count_all_results('m_galeri');
			$result[$key]->desc = $total.' media';
		}

		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? "Berhasil" : "",
			'data'		=> $result ? $result : [],
		], 200);
	}

	function get_direktori_post() {
		$id_direktori = $this->input->post('id_direktori');

		$result = $this->db->where('id_direktori', $id_direktori)->get('m_direktori')->row();
		if ($result) {
			$this->db->select('*,CONCAT("'.base_url($this->assets).'",file) as file');
			$result->galeri = $this->db->where('id_direktori', $id_direktori)->get('m_galeri')->result();
		}
		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? "Berhasil ditemukan" : "Tidak ditemukan",
			'data'		=> $result ? $result : null,
		], 200);
	}

	function alarm_post() {
		$id_user = $this->input->post('id_user');
		$status = ['ib1','ib2','ib3','bunting','kelahiran'];

		$this->db->select('*,id_sapi as id_alarm,
			CONCAT("'.base_url($this->assets).'",foto_pemilik) as foto_pemilik,
			CONCAT("'.base_url($this->assets).'",foto_sapi) as foto_sapi');
		$result = $this->db->where('id_inseminator', $id_user)
		->where_in('status', $status)->get('m_sapi')->result();

		foreach ($result as $key => $value) {
			$tgl_ib = ($value->ib_3) ? $value->ib_3 : 
			(($value->ib_2) ? $value->ib_2 : $value->ib_1);
			$result[$key]->umur_kebuntingan = '';
			$result[$key]->prediksi_tgl_lahir = '';
			$result[$key]->ib_calving = '';

			$today = new DateTime();

			if ($value->ib_1) {
				$ib_1 = new DateTime($value->ib_1);
				$result[$key]->ib_1 = $ib_1->format('d M Y');
			}

			if ($value->ib_2) {
				$ib_2 = new DateTime($value->ib_2);
				$result[$key]->ib_2 = $ib_2->format('d M Y');
			}

			if ($value->ib_3) {
				$ib_3 = new DateTime($value->ib_3);
				$result[$key]->ib_3 = $ib_3->format('d M Y');
			}

			if ($tgl_ib && $value->is_bunting=='1') {
				$tgl_ib_bunting = new DateTime($tgl_ib);
				$result[$key]->umur_kebuntingan = $today->diff($tgl_ib_bunting)->format("%a").' hari';

				$tgl_ib_bunting->modify('+84 day');
				$start_prediksi = $tgl_ib_bunting->format('d M Y');
				$tgl_ib_bunting->modify('+2 day');
				$end_prediksi = $tgl_ib_bunting->format('d M Y');
				$result[$key]->prediksi_tgl_lahir = $start_prediksi." - ".$end_prediksi;
			}

			if ($value->tgl_melahirkan) {
				$tgl_melahirkan = new DateTime($value->tgl_melahirkan);
				$result[$key]->tgl_melahirkan = $tgl_melahirkan->format('d M Y');

				$tgl_melahirkan->modify('+59 day');
				$start_calving = $tgl_melahirkan->format('d M Y');
				$tgl_melahirkan->modify('+2 day');
				$end_calving = $tgl_melahirkan->format('d M Y');
				$result[$key]->ib_calving = $start_calving." - ".$end_calving;
			}

			$status = '['.$value->no_sapi.' / '.$value->peternak.'] ';
			if (in_array($value->status, ['ib1','ib2','ib3'])) {

				$status .= "\nIB ke ".$value->status[2].", cek kebuntingan atau IB selanjutnya";
				$tgl_ib_temp = new DateTime($tgl_ib);
				$tgl_ib_temp->modify('+18 day');
				$result[$key]->desc = $tgl_ib_temp->format('d M Y');

			} else if ($value->status=='bunting') {

				$status .= 'Bunting, Prediksi Kelahiran';
				$result[$key]->desc = $result[$key]->prediksi_tgl_lahir;

			} else if ($value->status=='kelahiran') {
				$status .= 'IB Pasca Beranak';
				$result[$key]->desc = $result[$key]->ib_calving;
			}

			$result[$key]->title = $status;
			$result[$key]->active = 'on';
			$result[$key]->loop = 'off';
			$result[$key]->time = '9:00';

		}

		$this->response([
			'status'	=> $result ? true : true,
			'msg'		=> $result ? 'Berhasil' : '',
			'data'		=> $result ? $result : null,
		], 200);
	}

	function konsultan_get() {
		$this->db->select('*,CONCAT("'.base_url($this->assets).'",foto) as foto');
		$this->db->where('is_active', '1');
		$result = $this->db->get('m_konsultan')->result();

		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? 'Berhasil ditemukan' : 'Tidak ditemukan',
			'data'		=> $result ? $result : [],
		], 200);
	}


	function get_konsultan_post() {
		$id_konsultan = $this->input->post('id_konsultan');

		$this->db->select('*,CONCAT("'.base_url($this->assets).'",foto) as foto');
		$this->db->where('is_active', '1');
		$result = $this->db->where('id_konsultan', $id_konsultan)->get('m_konsultan')->row();
		
		$this->response([
			'status'	=> $result ? true : false,
			'msg'		=> $result ? 'Berhasil ditemukan' : 'Tidak ditemukan',
			'data'		=> $result ? $result : null,
		], 200);
	}


	function getOne($key, $value, $table) {
		return $this->db->where($key, $value)->get($table)->row();
	}

	function get_enum_field($table,$field)
	{
		$type = $this->db->query( "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'" )->row( 0 )->Type;
		preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
		$enum = explode("','", $matches[1]);
		return $enum;
	}

}