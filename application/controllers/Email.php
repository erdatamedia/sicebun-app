<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends CI_Controller {

	private function index()
	{
		$config = [
			'mailtype'  => 'text',
			'charset'   => 'utf-8',
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_user' => 'cattleweigh@gmail.com',
			'smtp_pass' => 'Lolitsapi007',
			'smtp_port' => 465,
			'crlf'      => "\r\n",
			'newline'   => "\r\n"
		];

		$this->load->library('email', $config);

		$this->email->from('cattleweigh@gmail.com', 'SIBOBA');
		$this->email->to('fadhlishobirin@gmail.com');

		$this->email->subject('Informasi Kode OTP Akun SIBOBA');
		$this->email->message('hahaha');

		if ($this->email->send()) {
			echo 'Sukses! email berhasil dikirim.';
		} else {
			echo 'Error! email tidak berhasil dikirim.';
			show_error($this->email->print_debugger());
		}
	}
}
