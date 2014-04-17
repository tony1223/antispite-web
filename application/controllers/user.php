<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {

	public function login(){
		
		if(is_login()){
			redirect(site_url("/"));
			return true;
		}
		
		$this->load->view('user/login',Array(
				"pageTitle" => "管理者登入" ,
				"selector" => "login"
		));
	}

	public function logining(){

		if(is_login()){
			redirect(site_url("/"));
			return true;
		}
		
		$account = $this->input->post("account");
		$pwd = $this->input->post("password");
		
		if($account == "" || $pwd == ""){
			redirect(site_url("user/login"));
			return true;
		}
	
		$this->load->model("userModel");
		$user = $this->userModel->find_user($account, $pwd);
		if($user == null){
			redirect(site_url("user/login"));
			return true;
		}
		unset($user["password"]);
		unset($user["_id"]);
		$_SESSION["suser"] = $user;
		redirect(site_url("/"));
	
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */