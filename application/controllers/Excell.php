<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Excell extends CI_Controller {

	public function index() {
		$this->load->view('excell');
	}

	public function read()
	{
		$path = './assets/';

		$this->upload_file_config($path, 'excell.xlsx');

		if ($this->upload->do_upload('file')){
			$uploaded = $this->upload->data();

			$filePath = $path.$uploaded['file_name'];

			$reader = ReaderEntityFactory::createReaderFromFile($filePath);

			$reader->open($filePath);

			$row = [];
			foreach ($reader->getSheetIterator() as $sheet) {
				foreach ($sheet->getRowIterator() as $row) {
					$cells = $row->getCells();

					echo "<pre>";
					print_r($cells[0]->getValue());
					echo "</pre>";
					if ($cells[0]->getValue()=='3') {
						break;
					}
				}
			}

			$reader->close();

			if (is_file($filePath) && file_exists($filePath)) {
				unlink($filePath);
			}

		} else {

			print_r( $this->upload->display_errors());
		}

	}

	public function upload_file_config($path='', $filename='')
	{
		$config['upload_path'] = $path;
		$config['allowed_types'] = '*';
		$config['max_size'] = '20480';
		$config['max_width']  = '10576';
		$config['max_height'] = '5536';
		$config['overwrite'] = true;
		// $config['encrypted'] = true;
		// $config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
	}
}
