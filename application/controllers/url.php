<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Url extends MY_Controller {
	
	public function resolver(){
		$this->load->model("urlModel");
		$urls = $this->urlModel->get_unsolved_urls();
		foreach($urls as $url){
			$result = $this->parse_url($url["_id"]);
			if($result[0] == "ok"){
				$this->urlModel->resolve_url($url["_id"],$result[1]);
			}else if($results[0] == "notitle"){
				$this->urlModel->resolve_url($url["_id"],"no-title");
			}else if($results[0] =="badurl"){
				$this->urlModel->resolve_url($url["_id"],"(不正確的網址)");
			}
		}
	}
	
	private function parse_url($url){

		$this->load->library("simple_html_dom");
		$content = file_get_contents($url);
		if(strpos($url,"udn.com") !== FALSE && strpos($url,"blog.udn.com") === FALSE){
			$content = iconv("big5","UTF-8//TRANSLIT//IGNORE",$content);
		}else if(strpos($content,'charset="big5"') !==FALSE || strpos($content,'charset=big5') !== FALSE){
			$content = iconv("big5","UTF-8//TRANSLIT//IGNORE",$content);
		}
				
		$oHtml = str_get_html($content );
		$title = array_shift($oHtml->find('title'))->innertext;
		return Array("ok",$title);		

// 		$remote_content = file_get_contents("http://decenturl.com/api-title?u=".rawurlencode($url));
// 		$result = json_decode($remote_content,true);
// 		return $result;		
	}
	
	public function test(){
		$url = "http://www.appledaily.com.tw/appledaily/article/headline/20140426/35792698/";
		$result = $this->parse_url($url);
		var_dump($result[1]);
		
	}
	
// 	public function review_urls(){
// 		$this->load->model("commentModel");
// 		$urls = $this->commentModel->review_urls();
// 	}
	
	public function index(){
		$this->load->model("urlModel");
		$urls = $this->urlModel->get_urls();

		$this->load->view('url/index',Array(
				"pageTitle" => "相關網址列表" ,
				"selector" => "url",
				"urls" => $urls
		));		
	}
	public function hot(){
		$this->load->model("urlModel");
		$urls = $this->urlModel->get_hot_urls();
	
		$this->load->view('url/index',Array(
				"pageTitle" => "相關網址列表" ,
				"selector" => "url",
				"urls" => $urls
		));
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */