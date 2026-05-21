<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Auth extends MX_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('m_auth');
	}

	function index()
	{
		if ($this->session->userdata('sicebun_user')) {
			redirect(base_url());
		}
		$page = 'auth/v_auth';
		$this->load->view($page);
	}

	function login()
	{
		$username = htmlspecialchars($this->input->post('username',TRUE),ENT_QUOTES);
		$password = htmlspecialchars($this->input->post('password',TRUE),ENT_QUOTES);

		$auth = $this->m_auth->auth($username,$password);

		if($auth){
			$this->session->set_userdata('sicebun_user',$auth);
			redirect(base_url('beranda'));
		} else {
			$this->session->set_flashdata('error_msg', 'username atau password salah');
			redirect(base_url('auth'));
		}

	}

	function logout()
	{
		$this->session->unset_userdata('sicebun_user');
		session_destroy();
		redirect(base_url('auth'));
	}

}
?>