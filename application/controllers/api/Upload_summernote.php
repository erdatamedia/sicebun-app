<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Upload_summernote extends REST_Controller {

	function __construct() {
		parent::__construct();
	}

	function index_get() {
		$this->response(null, 404);
	}

	function index_post(){
		$dir = $this->input->post('dir');
		$path = './assets/uploads/';
		if (isset($dir) && $dir != null) {
			$path = $path.$dir;
		}

		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$file = substr(str_shuffle($permitted_chars), 0, 11);
		$this->upload_file_config($path, $file);

		if ($this->upload->do_upload('file')){
			$uploaded = $this->upload->data();

			$url = base_url('assets/uploads/'.$dir.'/'.$uploaded['file_name']);

			$this->response($url, 200);
		} else {
			$this->response(false, 200);
		}
	}

}