<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends MY_Controller {
	
	public function confirm($type = 0,$page = 0){
		session_write_close();
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
		
		$this->load->model("commentModel");
		$comments = $this->commentModel->get_confirming(intval($type,10),intval($page,10));
		$stats = $this->commentModel->get_stats();
		$tokens = $this->commentModel->get_tokens();
		
		$this->load->view('comment/confirm',Array(
				"pageTitle" => "確認跳針留言" ,
				"selector" => "confirm",
				"comments" => $comments,
				"stats" => $stats,
				"tokens" => $tokens
		));
	}
	
	public function confirm_hot($page = 0){
		session_write_close();
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
	
		$this->load->model("commentModel");
		$comments = $this->commentModel->get_confirming_hot(intval($page,10));
		$stats = $this->commentModel->get_stats();
		$tokens = $this->commentModel->get_tokens();
	
		$this->load->view('comment/confirm',Array(
				"pageTitle" => "確認跳針留言" ,
				"selector" => "confirm",
				"comments" => $comments,
				"stats" => $stats,
				"tokens" => $tokens
		));
	}
	
	public function confirm_users($page = 0){
		session_write_close();
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
	
		$this->load->model("commentModel");
		$comments = $this->commentModel->get_confirming_users(intval($page,10));
		$stats = $this->commentModel->get_stats();
		$tokens = $this->commentModel->get_tokens();
	
		$this->load->view('comment/confirm',Array(
				"pageTitle" => "確認跳針留言" ,
				"selector" => "confirm",
				"comments" => $comments,
				"stats" => $stats,
				"tokens" => $tokens
		));
	}
	
	public function tag_user(){
		session_write_close();
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
	}
	
	public function confirmr($type = 0,$page = 0){
		session_write_close();
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
	
		$this->load->model("commentModel");
// 		$comments = $this->commentModel->get_confirming(intval($type,10),intval($page,10));
		
		$stats = $this->commentModel->get_stats();
		$tokens = $this->commentModel->get_tokens();
	
		$this->load->view('comment/confirm_react',Array(
				"pageTitle" => "確認跳針留言" ,
				"selector" => "confirm",
				"comments" => $comments,
				"stats" => $stats,
				"tokens" => $tokens
		));
	}
	
	public function search(){
		session_write_close();
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
		$search = $this->input->get("q");
		$page = $this->input->get("p");
		
		$this->load->model("commentModel");
		$comments = Array();
		
		if(! empty($search) ){
			$comments = $this->commentModel->search_check($search,intval($page,10));
		}
		$stats = $this->commentModel->get_stats();
		$tokens = $this->commentModel->get_tokens();
	
		$this->load->view('comment/confirm',Array(
				"pageTitle" => "確認跳針留言" ,
				"selector" => "confirm",
				"comments" => $comments,
				"stats" => $stats,
				"tokens" => $tokens
		));
	}
	
	public function add_token(){
		session_write_close();
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
		$token = $this->input->post("token");
		$this->load->model("commentModel");
		if(!empty($token)){
			$tokens = explode(",",$token);
			foreach($tokens as $toInsertedToken){
				$this->commentModel->insert_token($toInsertedToken);
			}
		}
		
		$tokens = $this->commentModel->get_tokens();
		
		$this->load->view('comment/add_token',Array(
				"pageTitle" => "跳針留言清單" ,
				"selector" => "comments",
				"tokens" => $tokens
		));
		
	}

	public function index(){
		session_write_close();
		$this->load->model("commentModel");
		$comments = $this->commentModel->get_bads();
	
		$this->load->view('comment/index',Array(
				"pageTitle" => "跳針留言清單" ,
				"selector" => "comments",
				"comments" => $comments
		));
	}
	

	public function provide(){
		session_write_close();
		$key = $this->input->get("key");
		$this->load->model("commentModel");
	
		$comment = $this->commentModel->get($key);
		
		if( $comment == null){
			return show_404();
		}
		
		$count = $this->commentModel->get_bad_count_by_user($comment["userkey"]);
	
		$this->load->view('comment/provide',Array(
				"pageTitle" => "提供留言參考資料" ,
				"selector" => "comments",
				"comment" => $comment,
				"count" => $count
		));
	}
	

	public function reply(){
		session_write_close();
		$info = $this->input->post("info");
		$url = $this->input->post("url");
		$id = $this->input->post("id");
		$this->load->model("commentModel");
		
		$comment = $this->commentModel->get($id);
		
		if($comment == null){
			return show_404();
		}
		
		if($url =="" && $info == "" || strpos($url,"onlinecasinoaustraliaaction.com") !== FALSE){
			return redirect(site_url("comment/thanks/?key=".$id));
		}
		
		$this->commentModel->insert_reply($id,$url,$info);
		redirect(site_url("comment/thanks/?key=".$id));
	}
	
	public function thanks(){
		session_write_close();
		$id = $this->input->get("key");
		$this->load->model("commentModel");
		$comment = $this->commentModel->get($id);
		if($comment == null){
			return show_404();
		}
		
		$this->load->view('comment/thanks',Array(
				"pageTitle" => "您的參考意見已送出" ,
				"selector" => "thanks",
				"comment" => $comment,
		));
	}
	

	public function user(){
		session_write_close();
		$key = $this->input->get("key");
		
		$status = $this->input->get("status");
		$this->load->model("commentModel");
		
		$page = $this->input->get("page");
		if(empty($page) || !is_numeric($page)){
			$page = 1;
		}
		$stats = Array();
		if(is_login()){
			$comments = $this->commentModel->get_all_by_user($key,$status,intval($page,10));
			$stats = $this->commentModel->get_stats_by_user($key);
		}else{
			$comments = $this->commentModel->get_bads_by_user($key,intval($page,10));
		}
		
		if(count($comments) == 0){
			return show_404();
		}
		$count = $this->commentModel->get_bad_count_by_user($key);
	
		$this->load->view('comment/user',Array(
			"pageTitle" => $comments[0]["name"]." 跳針留言清單" ,
			"selector" => "comments",
			"comments" => $comments,
			"count" => $count,
			"stats" => $stats
		));
	}
	
	public function url(){
		session_write_close();
		
		$url = $this->input->get("id");
		$this->load->model("commentModel");
		$this->load->model("urlModel");
		$url_item = $this->urlModel->get($url);
		
		if($url_item == null){
			return show_404();
		}
		
	
		if(is_login()){
			//https://tw.news.yahoo.com/%E5%8D%81%E8%90%AC%E7%BE%A4%E7%9C%BE%E9%9C%B8%E6%8D%B7%E9%81%8B-%E6%8D%B7%E9%81%8B%E8%AD%A6-%E5%85%88%E6%9F%94%E6%80%A7%E5%8B%B8%E9%9B%A2-052929940.html
			$comments = $this->commentModel->get_all_by_url($url);
		}else{
			$comments = $this->commentModel->get_bads_by_url($url);
		}
		if(count($comments) == 0){
			return show_404();
		}
		
		session_write_close();

		$title = isset($url_item["title"]) ? $url_item["title"] : $url_item["_id"];
		
		$count = $this->commentModel->get_bad_count_by_url($url);
	
		
		$this->load->view('comment/url',Array(
				"pageTitle" => $title." 相關跳針留言清單" ,
				"selector" => "comments",
				"comments" => $comments,
				"title" => $title,
				"count" => $count,
				"url" => $url_item
		)); 
	}
	
	public function rank(){
		session_write_close();
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
		session_write_close();
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
		$this->load->model("commentModel");
		$this->commentModel->mark($key,intval($status,10),$_SESSION["suser"]["account"]);
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
	
	public function mark_reply(){
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
		
		$status = intval($this->input->post("status"),10);
		$id = $this->input->post("id");
		
		$this->load->model("commentModel");
		$this->load->model("urlModel");
		
		$reply = $this->commentModel->get_reply($id);
		if($reply == null){
			return show_404();
		}
		
		$title = null;
		if($status == CommentModel::REPLY_OK ){
			$title = $this->urlModel->get_url_title($reply["url"]);
		}
		
		$this->commentModel->mark_reply($id,$status,$title);
		
	}
	
	public function check(){
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json; charset=utf-8');
		
		$posts = json_decode($this->input->post("posts"),"true");
		
		if($posts == null){
			return $this->return_error(400,"parameter not correct.");
		}
		
		$post_ids = Array();
		$users = Array();
		foreach($posts as $post){
			$post_ids[] = $post["key"];
			try{
				if(strpos($post["user"],"people/") !== FALSE){
					$keys = explode("/",$post["user"]);
					$post["user"]= "id=".$keys[2];
				}
			}catch(Exception $e){
			
			}
			$users[$post["type"].":".$post["user"]] = 1;
		}
		
		$this->load->model("commentModel");
		$bad_ids = $this->commentModel->check_ids($post_ids);
		$bad_users = $this->commentModel->check_users(array_keys($users));
		
		$check_ids = $this->commentModel->check_id_not_exists($post_ids);
		
		$client = "chrome";
		if($this->input->post("client") == "ff"){
			$client = "ff";
		}		
		
		$this->commentModel->insert_check_log($this->input->post("ueid"),$posts[0]["type"],$this->input->post("url"),$client);
// 		posts:all_post_ids,
// 		url:url
		//all_post_ids.push({key:nowpost.key,type:nowpost.type,user:nowpost.userkey});

		
		return $this->return_success_json(Array("bad_posts" => $bad_ids,"bad_users" => $bad_users, "check_ids" => $check_ids));
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
				return $this->return_error(400,"parameter not correct. [".$key."] ");
			}
			$inserting_data[$key] = $data->$key;
		}
		$this->load->model("commentModel");
		$this->load->model("urlModel");
		
		try{
			if($inserting_data["type"] == "FBComment" && strpos($inserting_data["userkey"],"people/") !== FALSE){
				$keys = explode("/",$inserting_data["userkey"]);
				$inserting_data["userkey"]= "id=".$keys[2];
			}
		}catch(Exception $e){
			
		}
		
		$inserting_data["url_title"] = $this->urlModel->get_url_title($inserting_data["url"]);
		
		if($this->input->post("exact") == "false"){
			$inserting_data["time_exact"] = false;
		}
		
		$check = false;
		if(isset($data->check) && $data->check == "true"){
			$check = true;
		}
		
		$client = "chrome";
		if($this->input->post("client") == "ff"){
			$client = "ff";
		}
		
		$ueid = $this->input->post("ueid");
		if($ueid == ""){
			return $this->return_error(400,"parameter not correct.");
		}
		$inserting_data["ueid"] = $ueid;
		$inserting_data["_id"] = $data->key;
		$this->commentModel->insert($inserting_data,$client,$check);
		
		
		$this->commentModel->update_wait_count($inserting_data["type"],$inserting_data["userkey"]);
		
		return $this->return_success_json();
	}
	
	public function report_check(){
		header("Access-Control-Allow-Origin: https://www.facebook.com");
		header('Content-Type: application/json; charset=utf-8');
	
		$data = json_decode($this->input->post("data"));
		if($data == null){
			return $this->return_error(400,"parameter not correct.");
		}
		$keys = Array("type","name","userkey","content","time","key","url");
	
		$inserting_data = Array();
		foreach ($keys as $key){
			if(!isset($data->$key)){
				return $this->return_error(400,"parameter not correct. [".$key."] ");
			}
			$inserting_data[$key] = $data->$key;
		}
		$this->load->model("commentModel");
		$this->load->model("urlModel");
	
		try{
			if($inserting_data["type"] == "FBComment" && strpos($inserting_data["userkey"],"people/") !== FALSE){
				$keys = explode("/",$inserting_data["userkey"]);
				$inserting_data["userkey"]= "id=".$keys[2];
			}
		}catch(Exception $e){
				
		}
	
		$inserting_data["url_title"] = $this->urlModel->get_url_title($inserting_data["url"]);
	
		if($this->input->post("exact") == "false"){
			$inserting_data["time_exact"] = false;
		}
	
		$client = "chrome";
		if($this->input->post("client") == "ff"){
			$client = "ff";
		}
	
		$ueid = $this->input->post("ueid");
		if($ueid == ""){
			return $this->return_error(400,"parameter not correct.");
		}
		$inserting_data["ueid"] = $ueid;
		$inserting_data["_id"] = $data->key;
		$this->commentModel->insert($inserting_data,$client,true);
		return $this->return_success_json();
	}
	
	public function reply_confirm(){
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}
		
		$this->load->model("commentModel");
		$comment_replys = $this->commentModel->get_reply_waiting();
		$stats = $this->commentModel->get_reply_stats();
		
		$this->load->view('comment/confirm_replys',Array(
			"pageTitle" => "確認跳針留言回應" ,
			"selector" => "confirm_reply",
			"comment_replys" => $comment_replys,
			"stats" => $stats
		));
	}
	
	public function removeReply(){
		if(!is_login()){
			redirect(site_url("user/login"));
			return true;
		}		
		$key = $this->input->get("key");
		$this->load->model("commentModel");
		$comment_replys = $this->commentModel->removeReply($key);
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */