<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Auth extends REST_Controller {

	private $assets = './assets/uploads/users/';

	function __construct() {
		parent::__construct();
	}

	function profile_post(){
		$email 		= $this->input->post('email');

		$status 	= false;
		$msg 		= 'Got';
		$result		= null;
		$data 		= $this->db
		->select('email,phone,ktp,ktp_file,selfie_file,fullname,is_verified,is_active')
		->where('email', $email)
		->get('users')->row();

		if ($data) {
			$result = $data;
			$status = true;
		}

		$this->response([
			'status'	=> $status,
			'msg'		=> $msg,
			'data'		=> $result,
		], 200);
	}

	function login_post(){
		$email 		= $this->input->post('email');
		$password 	= $this->input->post('password');

		$status 	= false;
		$msg 		= 'Tidak ditemukan';
		$result		= null;
		$data 		= $this->db
		->select('email,phone,ktp,password,fullname,is_verified,is_active')
		->where('email', $email)
		->get('users')->row();

		if ($data) {
			if ($data->password==md5($password)) {
				$status 		= true;
				if ($data->is_active) {
					$msg 		= 'Berhasil Login';
					$result 	= $data;
				} else {
					$msg 	= 'Email Belum Diaktivasi';
					$result = (object) [
						'is_active' => "0",
						'email' 	=> $email
					];
				}
			} else {
				$msg 		= 'Pin Salah';
			}
		} else {
			$msg 		= 'Email Tidak Ditemukan';
		}

		$this->response([
			'status'	=> $status,
			'msg'		=> $msg,
			'data'		=> $result,
		], 200);
	}

	function register_post() {
		$data['fullname'] 	= $this->input->post('fullname');
		$data['email'] 		= $this->input->post('email');
		$password 			= $this->input->post('password');
		$data['password'] 	= md5($password);

		$status 	= false;
		$msg 		= 'Pendaftaran Gagal';
		$result		= null;
		$isExist	= $this->db->where('email',$data['email'])->count_all_results('users');


		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$msg = 'Format email tidak valid';
			return $this->response([
				'status'	=> $status,
				'msg'		=> $msg,
				'data'		=> $result,
			], 200);
		}


		if (strlen($password)!=6) {
			$msg = 'PIN harus 6 digit';
			return $this->response([
				'status'	=> $status,
				'msg'		=> $msg,
				'data'		=> $result,
			], 200);
		}

		if ($isExist>0) {
			$msg 		= 'Email sudah terdaftar';
		} else {
			$otp 	= $this->send_email($data['email']);
			if($otp) {
				$this->db->insert('users', $data);
				$msg 		= 'Pendaftaran Berhasil';
				$new_id		= $this->db->insert_id();
				$result		= $this->db->where('id_user', $new_id)->get('users')->row();
				$status 	= true;
				$this->insertOtp($new_id, $otp);
			} else {
				$msg 		= 'Maaf ada kendala dengan sistem kami (Internal Server Error)';
			}
		}

		$this->response([
			'status'	=> $status,
			'msg'		=> $msg,
			'data'		=> $result,
		], 200);
	}

	function send_otp_again_post(){
		$email		= $this->input->post('email');

		$status 	= 200;
		$msg 		= 'Gagal Dikirim';
		$result		= null;

		$data		= $this->db->where('email',$email)->get('users')->row();

		if ($data) {
			$otp = $this->send_email($data->email);
			if ($otp) {
				$msg 		= 'Berhasil Dikirim, silahkan cek email anda';
				$status 	= true;
				$this->insertOtp($data->id_user, $otp);
			} else {
				$msg 		= 'Maaf ada kendala dengan sistem kami (Internal Server Error)';
			}
		}

		$this->response([
			'status'	=> $status,
			'msg'		=> $msg,
			'data'		=> $result,
		], 200);
	}

	function verify_otp_post() {
		$email		= $this->input->post('email');
		$otp		= $this->input->post('otp');

		$status 	= false;
		$msg 		= 'Gagal Verifikasi';
		$result		= null;

		$data		= $this->db->where('email',$email)->get('users')->row();
		if ($data) {
			$user_otp		= $this->db
			->where('id_user',$data->id_user)
			->where('otp',$otp)
			->get('users_otp')->row();
			if ($user_otp) {
				$request_date = strtotime($user_otp->request_date);
				$fiveMinutesAgo = strtotime('-5 minutes');
				if ($request_date >= $fiveMinutesAgo) {
					$msg 		= 'Kode OTP Valid';
					$status 	= true;
					$result 	= $this->verifyUser($data->id_user);
					$this->deleteOtp($data->id_user);
				} else {
					$msg 	= 'Kode OTP Kadaluarsa, kirim lagi';
				}
			} else {
				$msg 		= 'Kode OTP Tidak Valid';
			}
		}

		$this->response([
			'status'	=> $status,
			'msg'		=> $msg,
			'data'		=> $result,
		], 200);
	}

	function change_phone_post(){
		$email			= $this->input->post('email');
		$pin			= $this->input->post('pin');
		$phone			= $this->input->post('phone');

		$status 	= false;
		$msg 		= 'Gagal mengubah no. telepon';
		$result		= null;
		$data 		= $this->db
		->select('email,phone,ktp,password,fullname,is_verified,is_active')
		->where('email', $email)
		->get('users')->row();

		if ($data) {
			if (md5($pin)!=$data->password) {
				$msg = 'PIN Salah';
				return $this->response([
					'status'	=> $status,
					'msg'		=> $msg,
					'data'		=> $result,
				], 200);
			}
			$updated = $this->db->where('email',$email)->update('users',['phone'=>$phone]);
			if ($updated) {
				$status 			= true;
				$msg 				= 'No. Telp Berhasil Diganti';
				$data->phone		= $phone;
				$result 			= $data;
			}
		} else {
			$msg 		= 'Gagal, coba login lagi terlebih dahulu';
		}

		$this->response([
			'status'	=> $status,
			'msg'		=> $msg,
			'data'		=> $result,
		], 200);
	}

	function change_pin_post(){
		$email			= $this->input->post('email');
		$old_pin		= $this->input->post('old_pin');
		$new_pin		= $this->input->post('new_pin');
		$confirm_pin	= $this->input->post('confirm_pin');

		$status 	= false;
		$msg 		= 'Ubah PIN Gagal';
		$result		= null;
		$data 		= $this->db
		->select('email,phone,ktp,password,fullname,is_verified,is_active')
		->where('email', $email)
		->get('users')->row();


		if (strlen($new_pin)!=6) {
			$msg = 'PIN Baru harus 6 digit';
			return $this->response([
				'status'	=> $status,
				'msg'		=> $msg,
				'data'		=> $result,
			], 200);
		}

		if ($new_pin!=$confirm_pin) {
			$msg = 'Konfirmasi PIN Tidak Cocok';
			return $this->response([
				'status'	=> $status,
				'msg'		=> $msg,
				'data'		=> $result,
			], 200);
		}

		if ($data) {
			if (md5($old_pin)!=$data->password) {
				$msg = 'PIN Lama Salah';
				return $this->response([
					'status'	=> $status,
					'msg'		=> $msg,
					'data'		=> $result,
				], 200);
			}
			$updated = $this->db->where('email',$email)->update('users',['password'=>md5($new_pin)]);
			if ($updated) {
				$status 			= true;
				$msg 				= 'PIN Berhasil Diganti';
				$data->password 	= $new_pin;
				$result 			= $data;
			}
		} else {
			$msg 		= 'Gagal, coba login lagi terlebih dahulu';
		}


		$this->response([
			'status'	=> $status,
			'msg'		=> $msg,
			'data'		=> $result,
		], 200);
	}

	function verify_account_post(){
		$email			= $this->input->post('email');
		$fullname		= $this->input->post('fullname');
		$ktp			= $this->input->post('ktp');

		$status 	= false;
		$msg 		= 'Verifikasi Akun Gagal';
		$result		= null;
		$data 		= $this->db
		->select('email,ktp,ktp_file,selfie_file,fullname')
		->where('email', $email)
		->get('users')->row_array();

		$newdata = $data;

		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$file = substr(str_shuffle($permitted_chars), 0, 11);
		$this->upload_file_config($this->assets, $file);

		if ($this->upload->do_upload('ktp_file')){
			$uploaded = $this->upload->data();
			$this->resizeImage($this->assets, $uploaded['file_name']);
			$newdata['ktp_file'] = $uploaded['file_name'];

			if ($data) {
				$old_file = $this->assets.$data['ktp_file'];
				if (is_file($old_file) && file_exists($old_file)) {
					unlink($old_file);
				}
			}
		}

		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$file = substr(str_shuffle($permitted_chars), 0, 11);
		$this->upload_file_config($this->assets, $file);

		if ($this->upload->do_upload('selfie_file')){
			$uploaded = $this->upload->data();
			$this->resizeImage($this->assets, $uploaded['file_name']);
			$newdata['selfie_file'] = $uploaded['file_name'];

			if ($data) {
				$old_file = $this->assets.$data['selfie_file'];
				if (is_file($old_file) && file_exists($old_file)) {
					unlink($old_file);
				}
			}
		}

		$newdata['fullname']	= $fullname;
		$newdata['ktp']			= $ktp;
		$newdata['is_verified']	= '2';
		$updated = $this->db->where('email', $email)->update('users',$newdata);
		if ($updated) {
			$status 	= true;
			$msg 		= "Data berhasil disimpan, verisikasi butuh waktu 1-5 hari kerja";
			$result 	= $this->db->where('email', $email)->get('users')->row_array();
		}

		$this->response([
			'status'	=> $status,
			'msg'		=> $msg,
			'data'		=> $result,
		], 200);
	}

	function send_email($email){
		$numbers = '0123456789';
		$otp = substr(str_shuffle($numbers), 0, 4);

		$config = [
			'mailtype'  => 'html',
			'charset'   => 'utf-8',
			//'protocol'  => 'ssmtp',
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://mail.siboba.net',
			//'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_user' => 'admin@siboba.net',
			'smtp_pass' => 'Lolitsapi007',
			'smtp_port' => 465,
			'crlf'      => "\r\n",
			'newline'   => "\r\n"
		];

		$this->load->library('email', $config);

		$this->email->from('cattleweigh@gmail.com', 'SIBOBA');
		$this->email->to($email);

		$this->email->subject('Informasi Kode OTP Akun SIBOBA');

		$template = $this->load->view('otp',array('otp' => $otp),true);
		$this->email->message($template);

		if ($this->email->send()) {
			return $otp;
		} else {
			return false;
		}
	}

	function insertOtp($id_user, $otp) {
		$this->db->set('id_user',$id_user);
		$this->db->set('otp',$otp);
		$this->db->insert('users_otp');
	}

	function deleteOtp($id_user) {
		$this->db->where('id_user',$id_user);
		$this->db->delete('users_otp');
	}

	function verifyUser($id_user){
		$this->db->where('id_user',$id_user)->update('users',['is_active'=>1]);
		return $this->db->where('id_user',$id_user)->get('users')->row();
	}

}