<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author TonyQ
 *
 */
class UrlModel extends MONGO_MODEL {
	var $_collection = "urls";
	var $_collection_comment = "comment";
	
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
		return $this->mongo_db->where("resolved",false)->get($this->_collection);
	}
	

	public function get_urls(){
		return $this->mongo_db->whereGt("count",0)->orderBy("createDate","desc")->get($this->_collection);
	}
	public function get_hot_urls(){
		return $this->mongo_db->whereGt("count",0)->orderBy(Array("count" => "desc","createDate"=>"desc"))->get($this->_collection);
	}	
	
	
	public function resolve_url($url,$title){
		$this->mongo_db->where("_id",$url)
			->set("title",$title)
			->set("resolved",true)
			->set("resolved_date",time()*1000.0)
		->update($this->_collection);
		$this->mongo_db->where("url",$url)->where("url_title",null)
			->set("url_title",$title)
			->updateAll($this->_collection_comment);
		
	}
	
}
