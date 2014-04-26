<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Url extends MY_Controller {
	
	public function resolver(){
		$this->load->model("urlModel");
		$urls = $this->urlModel->get_unsolved_urls();
		foreach($urls as $url){
			$remote_content = file_get_contents("http://decenturl.com/api-title?u=".rawurlencode($url["_id"]));
			$result = json_decode($remote_content,true);
			
			if($result[0] == "ok"){
				$this->urlModel->resolve_url($url["_id"],$result[1]);
			}else if($results[0] == "notitle"){
				$this->urlModel->resolve_url($url["_id"],"no-title");
			}else if($results[0] =="badurl"){
				$this->urlModel->resolve_url($url["_id"],"(不正確的網址)");
			}
		}
	}
	
	public function review_urls(){
		$this->load->model("commentModel");
		$urls = $this->commentModel->review_urls();
		
	}
	
	public function index(){
		if(is_login()){
			return redirect(site_url("user/login"));
		}
		$this->load->model("urlModel");
		$urls = $this->urlModel->get_urls();

		$this->load->view('url/index',Array(
				"pageTitle" => "相關網址列表" ,
				"selector" => "urls",
				"urls" => $urls
		));		
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */