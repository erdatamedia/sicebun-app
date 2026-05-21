<?php 
class Users extends MX_Controller
{
	public $module = 'users';

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$module = $this->module;

		$data['module'] = $module;
		$data['title'] = 'Daftar Pengguna | SICEBUN';
		$page = $module.'/v_'.$module;
		$js = $module.'/js_'.$module;

		echo modules::run('template/loadview', $data, $page, $js);
	}
}
?>