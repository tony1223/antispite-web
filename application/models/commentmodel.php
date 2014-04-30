<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author TonyQ
 *
 */
class CommentModel extends MONGO_MODEL {
	
	var $_collection = "comment";
	var $_collection_user = "comment_user";
	var $_collection_record = "comment_record";
	var $_collection_log = "comment_log";
	var $_collection_urls = "urls";
	
	const STATUS_WAIT = 0;
	const STATUS_BAD = 1;
	const STATUS_OK = 2;
	 
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_confirming($status = 0,$page = 0){
		$pagesize = 500;
		$query =  $this->mongo_db->orderBy(Array("userkey" => "asc","createDate" => "desc") )->offset($page * $pagesize)->limit($pagesize);
		if($status != -1){
			$query->where("status",$status);
		}
		$items = $query->get($this->_collection);
		
		$users = Array();
		foreach($items as &$item){
			if(!isset($users[$item["userkey"]])){
				$result = $this->mongo_db->where(Array("user" => $item["userkey"]))->get($this->_collection_user);
				if(count($result) <= 0){
					$users[$item["userkey"]] = 0;
				}else{
					$users[$item["userkey"]] = $result[0]["count"];
				}				
			}
			$item["count"] = $users[$item["userkey"]];
			
				
		}
		return $items;
		
	}
	
	public function get_bads(){
		return $this->mongo_db->orderBy("time","desc")->where("status",CommentModel::STATUS_BAD)->limit(100)->get($this->_collection);
	}
	
	public function mark($key,$status,$user,$ueid){
		$now = time() *1000.0;
		
		$this->mongo_db->where("_id",$key)->set("status",$status)->set("status_update",$now)->update($this->_collection);
		$this->mongo_db->insert($this->_collection_record,
			Array("reporter"=> $ueid,
					"createDate" => $now,
					"target" => $key,
					"type" => "update_mark",
					"confirm_user" => $user,
					"status" => $status ));

		$items = $this->mongo_db->where("_id",$key)->get($this->_collection);
		if(count($items) > 0 ){
			$current = $items[0];
			
			//update user
			$now_count = $this->mongo_db->where(Array("userkey" => $current["userkey"],"status" => CommentModel::STATUS_BAD))->count($this->_collection);

			$exists = $this->mongo_db->where("_id",$current["type"].":".$current["userkey"])->count($this->_collection_user) > 0;
			
			if(!$exists){
				$this->mongo_db->insert($this->_collection_user,Array("_id" => $current["type"].":".$current["userkey"],"createDate" => $now));
			}
			
			$this->mongo_db->set(Array(
				"type" => $current["type"],
				"user" => $current["userkey"],
				"name" => $current["name"],
				"count" => $now_count,
				"last_update" => $now
			));
			$this->mongo_db->where("_id", $current["type"].":".$current["userkey"]);
			$this->mongo_db->update($this->_collection_user);
			
			//update urls
			$now_url_count = $this->mongo_db->where(Array("url" => $current["url"],"status" => CommentModel::STATUS_BAD))->count($this->_collection);

			$this->mongo_db->set(Array(
				"count" => $now_url_count,
				"last_count_update" => $now
			));
			$this->mongo_db->where("_id", $current["url"]);
			$this->mongo_db->update($this->_collection_urls);
			
		}
	}

	public function review_urls(){
		//update urls
		$urls = $this->mongo_db->get($this->_collection_urls);
		
		foreach($urls as $url){
			$now_url_count = $this->mongo_db->where(Array("url" => $url["_id"],"status" => CommentModel::STATUS_BAD))->count($this->_collection);
			
			$items = $this->mongo_db->where(Array("url" => $url["_id"]))->orderBy("time","asc")->limit(1)->get($this->_collection);
			$item = $items[0];
			$this->mongo_db->set(Array(
				"count" => $now_url_count,
				"last_count_update" => time()*1000.0,
				"createDate" => $item["time"],
			));
			$this->mongo_db->where("_id", $url["_id"]);
			$this->mongo_db->update($this->_collection_urls);
		}
			
	}
	
	//only used for rebuild users
	public function review(){
		$users = json_decode('[]',true);

		for($ind = 0 ; $ind < count($users);++$ind){
			$items = $this->mongo_db->where("userkey",$users[$ind])->orderBy("createDate","desc")->limit(1)->get($this->_collection);
			
			if(count($items) > 0 ){
				$current = $items[0];
				$now = $current["createDate"];
				$now_count = $this->mongo_db->where(Array("userkey" => $current["userkey"],"status" => CommentModel::STATUS_BAD))->count($this->_collection);
		
				$exists = $this->mongo_db->where("_id",$current["type"].":".$current["userkey"])->count($this->_collection_user) > 0;
					
				if(!$exists){
					$this->mongo_db->insert($this->_collection_user,Array("_id" => $current["type"].":".$current["userkey"],"createDate" => $now));
				}
					
				$this->mongo_db->set(Array(
						"type" => $current["type"],
						"user" => $current["userkey"],
						"name" => $current["name"],
						"count" => $now_count,
						"last_update" => $now
				));
				$this->mongo_db->where("_id", $current["type"].":".$current["userkey"]);
				$this->mongo_db->update($this->_collection_user);
					
			}
		}
	}
	
	public function check_ids($post_ids){
		return $this->mongo_db->select(Array("_id"))->whereIn("_id",$post_ids)->where("status",CommentModel::STATUS_BAD)->get($this->_collection);
	}
	
	public function check_users($users){
		$results = Array();
		foreach($users as $user){
			$user_count = $this->mongo_db->where("userkey",$user)->where("status",CommentModel::STATUS_BAD)->count($this->_collection);
			if($user_count > 0 ){
				$results[] = Array("user" => $user,"count" => $user_count);
			}
		}
		return $results;
	}
	
	public function get_bads_by_user($key){
		return $this->mongo_db->orderBy("time","desc")->where("userkey",$key)->where("status",CommentModel::STATUS_BAD)->limit(500)->get($this->_collection);
	}
	
	public function get_bad_count_by_user($key){
		return $this->mongo_db->where("userkey",$key)->where("status",CommentModel::STATUS_BAD)->count($this->_collection);
	}
	
	public function get_stats(){
		$bad = $this->mongo_db->where("status",CommentModel::STATUS_BAD)->count($this->_collection);
		$ok = $this->mongo_db->where("status",CommentModel::STATUS_OK)->count($this->_collection);
		$wait = $this->mongo_db->where("status",CommentModel::STATUS_WAIT)->count($this->_collection);
		
		return Array($wait,$bad,$ok);
		
	}
	
	public function insert_check_log($ueid,$type,$url){
		$now = time() *1000.0;
		$this->mongo_db->insert($this->_collection_log,
			Array("ueid"=>$ueid,
					"createDate" => $now,
					"type" => $type,
					"url" => $url)
		);
		
	}
	
	
	public function get_ranked_users($minial_bad = 0){
		$query = $this->mongo_db->orderBy("count","desc");
		if($minial_bad > 0 ){
			$query->whereGte("count",$minial_bad);
		}
		
		return $query->get($this->_collection_user);
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