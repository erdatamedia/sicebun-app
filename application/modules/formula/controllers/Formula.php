<?php 
class Formula extends MX_Controller
{
	public $module = 'formula';

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$module = $this->module;

		$data['module'] = $module;

		$data['title'] = 'Daftar Formula';
		$page = $module.'/v_'.$module;
		$js = $module.'/js_'.$module;

		echo modules::run('template/loadview', $data, $page, $js);
	}

}
?>