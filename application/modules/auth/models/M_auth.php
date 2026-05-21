<?php 
class M_auth extends CI_Model
{

	function auth($email,$password){
		$this->db->where('email', $email);
		$this->db->where('password', md5($password));
		$this->db->where('peran', 'admin');
		return $this->db->get('m_users')->row();
	}

}
?>