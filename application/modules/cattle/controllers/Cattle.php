<?php 
class Cattle extends MX_Controller
{
	public $module = 'cattle';

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$module = $this->module;

		$data['module'] = $module;

		$data['title'] = 'Daftar Sapi';
		$page = $module.'/v_'.$module;
		$js = $module.'/js_'.$module;

		echo modules::run('template/loadview', $data, $page, $js);
	}

	function history($id='')
	{
		if (!$id) {
			redirect(base_url('cattle'));
		}

		$module = 'history';

		$data['module'] = $module;
		$data['id'] = $id;

		$data['title'] = 'Daftar Riwayat ID '.$id;
		$page = $this->module.'/v_history';
		$js = $this->module.'/js_history';

		echo modules::run('template/loadview', $data, $page, $js);
	}

}
?>