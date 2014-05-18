<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author TonyQ
 *
 */
class UrlModel extends MONGO_MODEL {
	var $_collection = "urls";
	var $_collection_comment = "comment";
	var $_collection_reply = "comment_reply";
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_url_title($url){
		$results = $this->mongo_db->where("_id",$url)->limit(1)->get($this->_collection);
	
		if(count($results) <= 0){
			$this->mongo_db->insert($this->_collection,Array("_id" => $url,"createDate" => time()*1000.0,"title" => null,"resolved" => false));
			return null;
		}
		return $results[0]["title"];
	}
	
	public function get_unsolved_urls(){
		return $this->mongo_db->whereLte("fail",5)->where("resolved",false)->orderBy("createDate","desc")->limit(200)->get($this->_collection);
	}
	

	public function get_urls($order = "recent"){
		
		$query = $this->mongo_db->whereGt("count",0);
		
		if($order =="recent" ){
			$query->orderBy("createDate","desc");
		}else{
			$query->orderBy(Array("count" => "desc","createDate"=>"desc"));
		}
		
		return $query->get($this->_collection);
	}
	
	public function get_urls_for_api($order = "recent"){
	
		$query = $this->mongo_db->select(Array("_id","createDate","title","count"))->whereGt("count",0);
	
		if($order =="recent" ){
			$query->orderBy("createDate","desc");
		}else{
			$query->orderBy(Array("count" => "desc","createDate"=>"desc"));
		}
	
		return $query->get($this->_collection);
	}
	

	public function get($url){
		$results = $this->mongo_db->where("_id",$url)->get($this->_collection);
		
		return count($results) > 0 ? $results[0]: null;
	}
	
	public function get_hot_urls(){
		return $this->mongo_db->whereGt("count",0)->orderBy(Array("count" => "desc","createDate"=>"desc"))->get($this->_collection);
	}
	
	public function resolve_fail($url,$tried = null){
		$orig_url = $this->get($url);
		
		$fail = 0 ;
		if(isset($orig_url["fail"])){
			$fail = intval($orig_url["fail"],10);
		}
		$this->mongo_db->where("_id",$url)
		->set("fail",$fail +1 )
		->update($this->_collection);
	}
	
	public function resolve_url($url,$title){
		$this->mongo_db->where("_id",$url)
			->set("title",$title)
			->set("resolved",true)
			->set("resolved_date",time()*1000.0)
		->update($this->_collection);
		
		$this->mongo_db->where("url",$url)
			->set("url_title",$title)
			->updateAll($this->_collection_comment);
		
		$this->mongo_db->where("url",$url)
		->set("url_title",$title)
		->updateAll($this->_collection_reply);
		
		$this->mongo_db->where("reply.url",$url)
		->set("reply.url_title",$title)
		->updateAll($this->_collection_comment);
		
	}
	
}
