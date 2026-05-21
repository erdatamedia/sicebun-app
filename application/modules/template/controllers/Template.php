<?php
class Template extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('sicebun_user')) {
			redirect(base_url('auth'));
		}
	}

	function loadview($data=NULL,$page=NULL,$jscript=NULL)
	{
		$this->load->view('v_header',$data);
		$this->load->view('v_content',$data);
		if($page != NULL){
			$this->load->view($page,$data);
		} else {
			$this->load->view('v_content',$data);
		}
		$this->load->view('v_footer',$data);
		if($jscript != NULL){
			$this->load->view($jscript,$data);
		}
		$this->load->view('v_closing',$data);
	}
}
?>