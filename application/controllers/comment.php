<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends MY_Controller {
	
	public function confirm($type = 0,$page = 0){
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
		
		$this->load->model("commentModel");
		$comments = $this->commentModel->get_confirming(intval($type,10),intval($page,10));
		$stats = $this->commentModel->get_stats();
		
		$this->load->view('comment/confirm',Array(
				"pageTitle" => "確認跳針留言" ,
				"selector" => "confirm",
				"comments" => $comments,
				"stats" => $stats
		));		
	}

	public function index(){
		$this->load->model("commentModel");
		$comments = $this->commentModel->get_bads();
	
		$this->load->view('comment/index',Array(
				"pageTitle" => "跳針留言清單" ,
				"selector" => "comments",
				"comments" => $comments
		));
	}
	

	public function user(){
		$key = $this->input->get("key");
		$this->load->model("commentModel");
		$comments = $this->commentModel->get_bads_by_user($key);
		if(count($comments) == 0){
			return show_404();
		}
		$count = $this->commentModel->get_bad_count_by_user($key);
	
		$this->load->view('comment/user',Array(
				"pageTitle" => $comments[0]["name"]." 跳針留言清單" ,
				"selector" => "comments",
				"comments" => $comments,
				"count" => $count
		));
	}
	
	public function rank(){
		$key = $this->input->get("key");
		$this->load->model("commentModel");
		$users = $this->commentModel->get_ranked_users(10);
		if(count($users) == 0){
			return show_404();
		}
		
		$this->load->view('comment/rank',Array(
				"pageTitle" => "跳針排行榜" ,
				"selector" => "rank",
				"users" => $users
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
	
// 	public function review(){
// 		$this->load->model("commentModel");
// 		$this->commentModel->review();		
// 	}
	
	public function mark_ok(){
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
	}
	
	public function check(){
		header("Access-Control-Allow-Origin: https://www.facebook.com");
		header('Content-Type: application/json; charset=utf-8');
		
		$posts = json_decode($this->input->post("posts"),"true");
		
		if($posts == null){
			return $this->return_error(400,"parameter not correct.");
		}
		
		$post_ids = Array();
		$users = Array();
		foreach($posts as $post){
			$post_ids[] = $post["key"];
			$users[$post["user"]] = 1;
		}
		
		$this->load->model("commentModel");
		$bad_ids = $this->commentModel->check_ids($post_ids);
		$bad_users = $this->commentModel->check_users(array_keys($users));
		
		$this->commentModel->insert_check_log($this->input->post("ueid"),$posts[0]["type"],$this->input->post("url"));
// 		posts:all_post_ids,
// 		url:url
		//all_post_ids.push({key:nowpost.key,type:nowpost.type,user:nowpost.userkey});

		
		return $this->return_success_json(Array("bad_posts" => $bad_ids,"bad_users" => $bad_users));
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
		$this->load->model("commentModel");
		$this->load->model("urlModel");
		
		$inserting_data["url_title"] = $this->urlModel->get_url_title($inserting_data["url"]);
		
		if($this->input->post("exact") == "false"){
			$inserting_data["time_exact"] = false;
		}
		
		$ueid = $this->input->post("ueid");
		if($ueid == ""){
			return $this->return_error(400,"parameter not correct.");
		}
		$inserting_data["ueid"] = $ueid;
		
		$inserting_data["_id"] = $data->key;
		$this->commentModel->insert($inserting_data);
		return $this->return_success_json();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */