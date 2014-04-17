<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author TonyQ
 *
 */
class CommentModel extends MONGO_MODEL {
	
	var $_collection = "comment";
	var $_collection_record = "comment_record";
	function __construct()
	{
		parent::__construct();
	}
	
	public function insert($data){
		
// 		{
// 			type:"FBComment",
// 			name:username,
// 			userkey:userkey,
// 			content:content,
// 			time:time,
// 			key:key,
// 			url:url,
// 			ueid:chrome.runtime.id
// 		}
		$exist = $this->mongo_db->where("_id",$data["key"])->count($this->_collection);
		$now = time() * 1000.0;
		if($exist == 0 ){
			$data["reporters"] = Array($data["ueid"]);
			$data["createDate"] = $now;
			$data["enabled"] = false;
			$this->mongo_db->insert($this->_collection,$data);			
		}else{
			$this->mongo_db->where("_id",$data["key"])->addToSet("reporters",$data["ueid"])->update($this->_collection);
		}
		
		$this->mongo_db->insert($this->_collection_record, 
			Array("reporter"=>$data["ueid"], 
				"createDate" => $now,
				"target" => $data["key"],
				"type" => $data["type"],
				"userkey" => $data["userkey"] ));
		
	}

}
