<?php 
class Konsultan extends MX_Controller
{
	public $module = 'konsultan';

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$module = $this->module;

		$data['module'] = $module;

		$data['title'] = 'Daftar Konsultan | SICEBUN';
		$page = $module.'/v_'.$module;
		$js = $module.'/js_'.$module;

		echo modules::run('template/loadview', $data, $page, $js);
	}

}
?>