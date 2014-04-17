<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author TonyQ
 *
 */
class CommentModel extends MONGO_MODEL {
	
	var $_collection = "comment";
	var $_collection_record = "comment_record";
	const STATUS_WAIT = 0;
	const STATUS_BAD = 1;
	const STATUS_OK = 2;
	 
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_confirming(){
		return $this->mongo_db->orderBy("createDate","desc")->limit(100)->get($this->_collection);
	}
	
	public function get_bads(){
		return $this->mongo_db->orderBy("createDate","desc")->where("status",CommentModel::STATUS_BAD)->limit(100)->get($this->_collection);
	}
	
	public function mark($key,$status,$user){
		$now = time() *1000.0;
		$this->mongo_db->where("_id",$key)->set("status",$status)->set("status_update",$now)->update($this->_collection);
		$this->mongo_db->insert($this->_collection_record,
			Array("reporter"=>$data["ueid"],
					"createDate" => $now,
					"target" => $key,
					"type" => "update_mark",
					"confirm_user" => $user,
					"status" => $status ));		
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
			$data["status"] = CommentModel::STATUS_WAIT;
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
