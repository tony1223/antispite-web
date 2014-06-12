<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Url extends MY_Controller {
	
	public function resolver(){
		session_write_close();
		$this->load->model("urlModel");
		$urls = $this->urlModel->get_unsolved_urls();
		foreach($urls as $url){
			$result = $this->parse_url($url["_id"]);
			try{
				if($result[0] == "ok"){
					$this->urlModel->resolve_url($url["_id"],$result[1]);
				}else if($result[0] == "notitle"){
					$this->urlModel->resolve_url($url["_id"],"no-title");
				}else if($result[0] =="badurl"){
					$this->urlModel->resolve_url($url["_id"],"(不正確的網址)");
				}else{
					$this->urlModel->resolve_fail($url["_id"]);
					echo "can't resolve:".$url["_id"]."<br />";
				}
				echo $result[0];
			}catch(Exception $ex){
				$this->urlModel->resolve_fail($url["_id"],$result[1]);
				echo " resolve but got exception:".$url["_id"]."<br />";
			}
		}
	}
	
	public function todo(){
		session_write_close();
		$this->load->model("urlModel");
		$urls = $this->urlModel->get_unsolved_urls();
		foreach($urls as $url){
			$result = $this->_page_title($url["_id"]);
			echo $url["_id"].":::[".$url["fail"]."]- ".$result[1]."<Br />";
		}
	}
	
	private function _page_title($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$fp = curl_exec($ch);
		if (!$fp)
			return Array("exception",null);
	
		$res = preg_match("/<title>(.*)<\/title>/siU", $fp, $title_matches);
		if (!$res)
			return Array("exception",null);
	
		// Clean up title: remove EOL's and excessive whitespace.
		$title = preg_replace('/\s+/', ' ', $title_matches[1]);
		$title = trim($title);
		return Array("ok",$title);
	}
	
	private function parse_url($url){

		try{
			$this->load->library("simple_html_dom");
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$content = curl_exec($ch);
			
			if(strpos($url,"www.nownews.com") !== FALSE){
				//do nothing 
			}else	if(strpos($url,"udn.com") !== FALSE && strpos($url,"blog.udn.com") === FALSE){
				$content = @iconv("big5","UTF-8//TRANSLIT//IGNORE",$content);
			}else if(strpos($content,'charset="big5"') !==FALSE || strpos($content,'charset=big5') !== FALSE){
				$content = @iconv("big5","UTF-8//TRANSLIT//IGNORE",$content);
			}else if(strpos($url,"www.nownews.com") !== FALSE ){
				$content = @iconv("big5","UTF-8//TRANSLIT//IGNORE",$content);
			}
			$oHtml = str_get_html($content );
			
			if($oHtml == null){
				return Array("exception",null);
			}			
			$title = array_shift($oHtml->find('title'))->innertext;
			return Array("ok",$title);
		}catch(Exception $ex){
			return Array("exception",$title);
		}		

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