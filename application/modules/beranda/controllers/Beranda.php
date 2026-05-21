<?php 
class Beranda extends MX_Controller
{
	public $module = 'beranda';

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$module = $this->module;

		$data['total'] = $this->getTotalSapi();
		$data['total_per_inseminator'] = $this->getTotalSapiPerInsemniator();
		$data['total_per_status'] = $this->getTotalSapiPerStatus();
		$data['sapi_per_provinsi'] = json_encode($this->getTotalSapiPerWilayah('provinsi'));
		$data['sapi_per_kota'] = json_encode($this->getTotalSapiPerWilayah('kota'));
		$data['sapi_per_kecamatan'] = json_encode($this->getTotalSapiPerWilayah('kecamatan'));
		$data['sapi_per_kelurahan'] = json_encode($this->getTotalSapiPerWilayah('kelurahan'));

		$data['module'] = $module;
		$data['title'] = 'Beranda';
		$page = $module.'/v_'.$module;
		$js = $module.'/js_'.$module;

		echo modules::run('template/loadview', $data, $page, $js);
	}

	function getTotalSapi() {
		return $this->db->count_all_results('m_sapi');
	}

	function getTotalSapiPerInsemniator() {
		$this->db->select('m1.name,COUNT(m2.id_inseminator) as total');
		$this->db->join('m_sapi m2', 'm2.id_inseminator=m1.id_user','left');
		$this->db->group_by('m2.id_inseminator');
		return $this->db->get('m_users m1')->result();
	}

	function getTotalSapiPerWilayah($wilayah='') {
		$provinsi = $this->input->get('provinsi');
		$kota = $this->input->get('kota');
		$kecamatan = $this->input->get('kecamatan');

		$this->db->select($wilayah.' as name, count(id_sapi) as total');
		$this->db->order_by('count(id_sapi)');
		$this->db->group_by($wilayah);
		if ($wilayah != 'provinsi' && $provinsi) {
			$this->db->where('provinsi', $provinsi);
		}
		if ($wilayah != 'kota' && $kota) {
			$this->db->where('kota', $kota);
		}
		if ($wilayah != 'kecamatan' && $kecamatan) {
			$this->db->where('kecamatan', $kecamatan);
		}
		return $this->db->get('m_sapi')->result();
	}


	function getTotalSapiPerStatus() {
		$status = [
			[
				'initial'=>'ib1',
				'status'=>'Inseminasi Buatan 1',
			],
			[
				'initial'=>'ib2',
				'status'=>'Inseminasi Buatan 2',
			],
			[
				'initial'=>'ib3',
				'status'=>'Inseminasi Buatan 3',
			],
			[
				'initial'=>'bunting',
				'status'=>'Bunting',
			],
			[
				'initial'=>'tidak_bunting',
				'status'=>'Tidak Bunting',
			],
			[
				'initial'=>'gangrep',
				'status'=>'Gangguan Reproduksi',
			],
			[
				'initial'=>'kelahiran',
				'status'=>'Kelahiran',
			],
		];
		foreach ($status as $key => $value) {
			$total = $this->db->where('status', $value['initial'])->count_all_results('m_sapi');
			$status[$key] = (object)[
				'initial'=>$value['initial'],
				'status'=>$value['status'],
				'total'=>$total,
			];
		}
		return $status;
	}

}
?>