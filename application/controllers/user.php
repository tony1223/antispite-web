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
	
	public function reigster(){
		if(is_login()){
			redirect(site_url("/"));
			return true;
		}
		
		$this->load->view('user/regist',Array(
				"pageTitle" => "管理者註冊" ,
				"selector" => "login"
		));
	}
	
	public function registering(){
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
		$success = $this->userModel->insert($account, $pwd);
		
		if($success){
			echo "註冊完成";
		}else{
			echo "帳號重複";
		}
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
		$user = $this->userModel->find_valid_user($account, $pwd);
		if($user == null){
			redirect(site_url("user/login"));
			return true;
		}
		unset($user["password"]);
		unset($user["_id"]);
		$_SESSION["suser"] = $user;
		redirect(site_url("comment/confirm"));
	
	}
	
	public function logout(){
		unset($_SESSION["suser"]); 
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */