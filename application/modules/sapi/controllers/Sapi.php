<?php 

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Sapi extends MX_Controller
{
	public $module = 'sapi';

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$module = $this->module;

		$data['module'] = $module;
		$data['provinsi'] = array_column($this->db->get('m_provinsi')->result_array(), 'nama');
		$data['kota'] = array_column($this->db->get('m_kota')->result_array(), 'nama');
		$data['kecamatan'] = array_column($this->db->get('m_kecamatan')->result_array(), 'nama');
		$data['kelurahan'] = array_column($this->db->get('m_kelurahan')->result_array(), 'nama');

		$data['title'] = 'Daftar Sapi | SICEBUN';
		$page = $module.'/v_'.$module;
		$js = $module.'/js_'.$module;

		echo modules::run('template/loadview', $data, $page, $js);
	}

	function import() {

		$module = 'import';

		$data['module'] =  $module;
		$data['data'] = $this->read();
		$id_inseminator = $this->input->post('id_inseminator');
		$inseminator = $this->db->where('id_user',$id_inseminator)->get('m_users')->row();
		$data['id_inseminator'] = $id_inseminator;

		$data['title'] = 'Import Sapi | SICEBUN';
		$page = 'sapi/v_'.$module;
		$js = 'sapi/js_'.$module;

		echo modules::run('template/loadview', $data, $page, $js);
	}

	public function save() {
		$id_inseminator = $this->input->post('id_inseminator[]');
		$no_sapi = $this->input->post('no_sapi[]');
		$peternak = $this->input->post('peternak[]');
		$alamat = $this->input->post('alamat[]');
		$tgl_lahir = $this->input->post('tgl_lahir[]');

		foreach ($no_sapi as $key => $value) {
			$this->db->insert('m_sapi', [
				'id_inseminator'=>$id_inseminator,
				'no_sapi'=>$value,
				'peternak'=>$peternak[$key],
				'alamat'=>$alamat[$key],
				'tgl_lahir'=>$tgl_lahir[$key],
			]);
		}
		redirect(base_url('sapi'));
	}

	private function read()
	{
		$path = './assets/';
		$res = [];

		$this->upload_file_config($path, 'temp.xlsx');

		if ($this->upload->do_upload('file')){
			$uploaded = $this->upload->data();

			$filePath = $path.$uploaded['file_name'];

			$reader = ReaderEntityFactory::createReaderFromFile($filePath);

			$reader->open($filePath);

			foreach ($reader->getSheetIterator() as $sheet) {
				foreach ($sheet->getRowIterator() as $row) {
					$cells = $row->getCells();

					array_push($res, [
						'no_sapi' => $cells[0]->getValue(),
						'peternak' => $cells[1]->getValue(),
						'alamat' => $cells[2]->getValue(),
						'tgl_lahir' => $cells[3]->getValue(),
					]);
				}
			}

			$reader->close();

			if (is_file($filePath) && file_exists($filePath)) {
				unlink($filePath);
			}

			array_shift($res);
		}

		return $res;
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

	function get_enum_provinsi($table,$field)
	{
		$type = $this->db->query( "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'" )->row( 0 )->Type;
		preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
		$enum = explode("','", $matches[1]);
		foreach ($enum as $key => $value) {
			$name = ucwords(str_replace('_', ' ', $value));
			$enum[$key] = [
				'name'=> $name,
				'value'=>$value
			];
		}
		return $enum;
	}
}
?>