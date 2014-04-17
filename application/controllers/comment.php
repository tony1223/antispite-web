<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends MY_Controller {
	
	public function confirm(){
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
		
		$this->load->model("commentModel");
		$comments = $this->commentModel->get_confirming();
		
		$this->load->view('comment/confirm',Array(
				"pageTitle" => "確認惡意留言" ,
				"selector" => "confirm",
				"comments" => $comments
		));		
	}

	public function index(){
		$this->load->model("commentModel");
		$comments = $this->commentModel->get_bads();
	
		$this->load->view('comment/index',Array(
				"pageTitle" => "跳針留言清單" ,
				"selector" => "confirm",
				"comments" => $comments
		));
	}
	
	
	public function mark($key,$status){
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
		$this->load->model("commentModel");
		$this->commentModel->mark($key,intval($status,10),$_SESSION["suser"]["account"]);
		redirect(site_url("comment/confirm"));
	}
	
	public function mark_ok(){
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
	}
	
	public function report(){
		header("Access-Control-Allow-Origin: https://www.facebook.com");
		header('Content-Type: application/json; charset=utf-8');
		
		$data = json_decode($this->input->post("data"));
		
		/*
		 * {
              type:"FBComment",
              name:username,
              userkey:userkey,
              content:content,
              time:time,
              key:key,
              url:url,
              ueid:chrome.runtime.id
            }
		 */
		
		if($data == null){
			return $this->return_error(400,"parameter not correct.");
		}
		$keys = Array("type","name","userkey","content","time","key","url");
		
		$inserting_data = Array();
		foreach ($keys as $key){
			if(!isset($data->$key)){
				return $this->return_error(400,"parameter not correct.");
			}
			$inserting_data[$key] = $data->$key;
		}
		
		$ueid = $this->input->post("ueid");
		if($ueid == ""){
			return $this->return_error(400,"parameter not correct.");
		}
		$inserting_data["ueid"] = $ueid;
		
		$inserting_data["_id"] = $data->key;
		$this->load->model("commentModel");
		$this->commentModel->insert($inserting_data);
		return $this->return_success_json();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */