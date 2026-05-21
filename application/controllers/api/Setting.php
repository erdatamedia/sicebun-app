<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Setting extends REST_Controller {

	function __construct() {
		parent::__construct();
		if (!$this->session->userdata('sicebun_user')) {
			$this->response(null,404);
		}
	}

	function changepass_post() {
		$old_password = $this->input->post('old_password');
		$data['password'] = md5($this->input->post('new_password'));

		if (strlen($this->input->post('new_password'))<4) {
			$this->response((object)array(
				'status' => false,
				'message' => 'Panjang password baru min 4',
			), 200);
		}
		
		$this->db->where('email', $this->session->userdata('sicebun_user')->email);
		$this->db->where('password', md5($old_password));
		$result = $this->db->update('m_users', $data);

		if ($this->db->affected_rows()>0) {
			$this->response((object)array(
				'status' => true,
				'message' => 'Password berhasil diubah',
			), 200);
		} else {
			$this->response((object)array(
				'status' => false,
				'message' => 'Password gagal diubah',
			), 200);
		}
	}

}