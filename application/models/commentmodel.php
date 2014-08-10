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
	var $_collection_tokens = "comment_tokens";
	
	var $_collection_reply = "comment_reply";
	
	const STATUS_CHECK = -1;
	const STATUS_WAIT = 0;
	const STATUS_BAD = 1;
	const STATUS_OK = 2;
	
	const REPLY_WAIT = 0;
	const REPLY_HISTORY = 1;
	const REPLY_OK = 2;
	 
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_tokens(){
		return $this->mongo_db->get($this->_collection_tokens);
	}
	
	public function insert_token($token){
		$this->mongo_db->insert($this->_collection_tokens,Array("_id" => $token));
	}
	
	public function search_check($keyword,$page){
		$pagesize = 10;
		$query =  $this->mongo_db->orderBy(Array("userkey" => "asc","createDate" => "desc") )
			->offset($page * $pagesize)
			->limit($pagesize);
		$query->whereLike("content" , $keyword);
		$query->whereIn("status",Array(CommentModel::STATUS_CHECK,CommentModel::STATUS_WAIT));
		$items=  $query->get($this->_collection);
		$items = $this->_merge_user_count($items);
		return $items;
	}
	
	public function _merge_user_count(&$items){
		$users = Array();
		foreach($items as &$item){
			if(!isset($users[$item["userkey"]])){
				$result = $this->mongo_db->where(Array("user" => $item["userkey"]))->get($this->_collection_user);
				if(count($result) <= 0){
					$users[$item["userkey"]] = Array(
							"count"=>0 ,
							"all_count" => -1,
							"wait_count" => -1,
							"bad_count" => -1,
							"check_count" => -1
					) ;
				}else{
					$users[$item["userkey"]] = $result[0];
					if(!isset($result[0]["wait_count"])){
						$users[$item["userkey"]]["all_count"] =  -1;
						$users[$item["userkey"]]["bad_count"] =  -1;
						$users[$item["userkey"]]["wait_count"] =  -1;
						$users[$item["userkey"]]["check_count"] =  -1;
					}
				}
			}
			$item["count"] = $users[$item["userkey"]];
		
		}
		return $items;
	}
	
	public function get_confirming($status = 0,$page = 0){
		
		$pagesize = 1000;
		$query =  $this->mongo_db->orderBy(Array("userkey" => "asc","createDate" => "desc") )->offset($page * $pagesize)->limit($pagesize);
		if($status != -1){
			$query->where("status",$status);
		}
		$items = $query->get($this->_collection);
		$this->_merge_user_count($items);
		return $items;
	}
	
	public function get_confirming_hot($page = 0){
	
		$pagesize = 1000;
		$query =  $this->mongo_db->orderBy(Array("userkey" => "asc","createDate" => "desc") )->offset($page * $pagesize)->limit($pagesize);
		$query->where("reporters.3",array("\$exists" => true));
		$query->where("status",0);
		$items = $query->get($this->_collection);
		$this->_merge_user_count($items);
		
		return $items;
	}

	public function get_confirming_users($page = 0){
	
		$pageSize = 50;
		$users = $this->mongo_db->orderBy("wait_count","desc")
			->limit($pageSize)->offset($page * $pageSize)->get($this->_collection_user);
		

		$results = Array();
		foreach($users as $user){
			$query =  $this->mongo_db->orderBy(Array("userkey" => "asc","createDate" => "desc") );
			$query->where("status",0);
			$query->where("userkey",$user["user"]);
			$items = $query->get($this->_collection);
			
			foreach($items as $item){
				$results[] = $item;
			}
		}
		$this->_merge_user_count($results);
	
		return $results;
	}
	
	
	public function get_bads(){
		return $this->mongo_db->orderBy("time","desc")->where("status",CommentModel::STATUS_BAD)->limit(100)->get($this->_collection);
	}
	
	public function mark($key,$status,$user){
		$now = time() *1000.0;
		
		$this->mongo_db->where("_id",$key)->set("status",$status)->set("status_update",$now)->update($this->_collection,Array("w"=>0));
		$this->mongo_db->insert($this->_collection_record,
			Array(
					"createDate" => $now,
					"target" => $key,
					"type" => "update_mark",
					"confirm_user" => $user,
					"status" => $status ),Array("w"=>0));

		$items = $this->mongo_db->where("_id",$key)->limit(1)->get($this->_collection);
		if(count($items) > 0 ){
			$current = $items[0];
			
			//update user
			$this->update_user_count($current["type"],$current["userkey"],$current["name"]);
			
			//update urls
			$now_url_count = $this->mongo_db->where(Array("url" => $current["url"],"status" => CommentModel::STATUS_BAD))->count($this->_collection);
			$all_url_count = $this->mongo_db->where(Array("url" => $current["url"]))->count($this->_collection);

			$this->mongo_db->set(Array(
				"count" => $now_url_count,
				"all_count" => $all_url_count,
				"last_count_update" => $now
			));
			$this->mongo_db->where("_id", $current["url"]);
			$this->mongo_db->update($this->_collection_urls,Array("w"=>0));
			
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
			$this->mongo_db->update($this->_collection_urls,Array("w"=>0));
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
				$this->mongo_db->update($this->_collection_user,Array("w"=>0));
					
			}
		}
	}
	
	public function check_ids($post_ids){
		return $this->mongo_db->select(Array("_id","reply.url_title","reply.url","reply.content","reply.createDate"))->whereIn("_id",$post_ids)->where("status",CommentModel::STATUS_BAD)->get($this->_collection);
	}
	
	public function check_id_not_exists($post_ids){
		$map = Array();

		foreach($post_ids as $id){
			$map[$id] = 1;
		}
		
		$ids = $this->mongo_db->select("_id")->whereIn("_id",$post_ids)->get($this->_collection);
		
		foreach($ids as $id){
			if(!isset($map[$id["_id"]])){
				die("error");
			}
			unset($map[$id["_id"]]);
		}
		
		return array_keys($map);
	}
	
	public function check_users($users){
		$results = Array();
		

		//optimzed query
		$query_results = $this->mongo_db->select(Array("user","bad_count"))->whereGt("bad_count",0)->whereIn("_id",$users)->get($this->_collection_user);
		
		foreach($query_results as $user){
			$results[] = Array("user" => $user["user"],"count" => isset($user["bad_count"]) ? $user["bad_count"] : 0 );
		}
		
		return $results;
	}
	
	public function get_bads_by_user($key,$page = 1){
		$pageSize = 500;
		$query= $this->mongo_db->orderBy("time","desc")->where("userkey",$key)->where("status",CommentModel::STATUS_BAD)->limit($pageSize);
		
		$query->offset($pageSize * ($page -1 ));
		
		return $query->get($this->_collection);
	}
	
	public function get_bads_by_user_all($key){
		return $this->mongo_db->select(Array("_id","type","name","userkey","content","time","url","url_title",
				"reply.url_title","reply.url","reply.content","reply.createDate"))->orderBy("time","desc")->where("userkey",$key)->where("status",CommentModel::STATUS_BAD)->get($this->_collection);
	}

	public function get_recent_bads(){
		return $this->mongo_db->select(Array("_id","type","name","userkey","content","time","url","url_title",
				"reply.url_title","reply.url","reply.content","reply.createDate"))->orderBy("time","desc")
				->where("status",CommentModel::STATUS_BAD)->limit(200)->get($this->_collection);
	}
	
	public function get_all_by_user($key,$status = null,$page = 1){
		$pageSize = 500;
		$query = $this->mongo_db->orderBy("time","desc")->where("userkey",$key)->limit($pageSize);
		$query->offset($pageSize * ($page -1 ));
		if($status =="" || $status == null){
			return $query->get($this->_collection);
		}
		return $query->where("status",intval($status,10))->get($this->_collection);
	}
	
	public function get_bads_by_url($key){
		return $this->mongo_db->orderBy("time","desc")->where("url",$key)->where("status",CommentModel::STATUS_BAD)->limit(500)->get($this->_collection);
	}
	
	public function get_all_by_url($key){
		return $this->mongo_db->orderBy("time","desc")->where("url",$key)->limit(500)->get($this->_collection);
	}
	
	public function get($key){
		$results = $this->mongo_db->where("_id", $key )->limit(1)->get($this->_collection);
		if(count($results) <= 0 ){
			return null;
		}
		return $results[0];
	}
	
	
	public function get_bad_count_by_user($key){
		return $this->mongo_db->where("userkey",$key)->where("status",CommentModel::STATUS_BAD)->count($this->_collection);
	}
	
	public function get_bad_count_by_url($key){
		return $this->mongo_db->where("url",$key)->where("status",CommentModel::STATUS_BAD)->count($this->_collection);
	}
	
	public function get_stats(){
		$bad = $this->mongo_db->where("status",CommentModel::STATUS_BAD)->count($this->_collection);
		$ok = $this->mongo_db->where("status",CommentModel::STATUS_OK)->count($this->_collection);
		$wait = $this->mongo_db->where("status",CommentModel::STATUS_WAIT)->count($this->_collection);
		$check = $this->mongo_db->where("status",CommentModel::STATUS_CHECK)->count($this->_collection);
		
		return Array($wait,$bad,$ok,$check);
	}
	
	public function get_stats_by_user($userkey){
		$bad = $this->mongo_db->where("userkey",$userkey)->where("status",CommentModel::STATUS_BAD)->count($this->_collection);
		$ok = $this->mongo_db->where("userkey",$userkey)->where("status",CommentModel::STATUS_OK)->count($this->_collection);
		$wait = $this->mongo_db->where("userkey",$userkey)->where("status",CommentModel::STATUS_WAIT)->count($this->_collection);
		$check = $this->mongo_db->where("userkey",$userkey)->where("status",CommentModel::STATUS_CHECK)->count($this->_collection);
	
		return Array($wait,$bad,$ok,$check);
	}
	
	public function get_reply_stats(){
		$bad = $this->mongo_db->where("status",CommentModel::REPLY_OK)->count($this->_collection_reply);
//		$ok = $this->mongo_db->where("status",CommentModel::REPLY_HISTORY)->count($this->_collection_reply);
		$wait = $this->mongo_db->where("status",CommentModel::REPLY_WAIT)->count($this->_collection_reply);
		return Array($wait,$bad);
		
	}
	
	public function insert_check_log($ueid,$type,$url,$client = "chrome"){
		$now = time() *1000.0;
		$this->mongo_db->insert($this->_collection_log,
			Array(
				"ueid"=>$ueid,
				"createDate" => $now,
				"type" => $type,
				"url" => $url,
				"client" => $client
			)
		);
		
	}
	
	
	public function get_ranked_users($minial_bad = 0){
		$query = $this->mongo_db->orderBy("count","desc");
		if($minial_bad > 0 ){
			$query->whereGte("count",$minial_bad);
		}
		
		$query->limit(500);
		
		return $query->get($this->_collection_user);
	}
	
	public function insert_user_tags($userID,$tags){
		
// 		$this->
		
	}
	
	public function insert($data,$client = "chrome",$check = false){
		
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
		$exist = $this->mongo_db->select(Array("status","reporters"))->where("_id",$data["key"])->get($this->_collection);
		$now = time() * 1000.0;
		if(count($exist) == 0 ){
			$data["createDate"] = $now;

			$data["creator"] = $data["ueid"]; //TODO:refine old data (from reporters[0] => creator)
			if($check){
				$data["status"] = CommentModel::STATUS_CHECK;
				$data["reporters"] = Array();
				$data["reporter_count"] = 0 ;
			}else{
				$data["status"] = CommentModel::STATUS_WAIT;
				$data["reporters"] = Array($data["ueid"]);
				$data["reporter_count"] = 1 ;
			}
			$this->mongo_db->insert($this->_collection,$data);			
		}else if(!$check){
			
			$query = $this->mongo_db->where("_id",$data["key"])->addToSet("reporters",$data["ueid"]);
			if($exist[0]["status"] == CommentModel::STATUS_CHECK ){
				$query->set("status",  CommentModel::STATUS_WAIT);
			}
			
			$find = false;
				
			foreach($exist[0]["reporters"] as $reporter){
				if($reporter == $data["ueid"]){
					$find = true;
				}
			}
						
			if(!$find){
				$query->set("reporter_count", count($exist[0]["reporters"]) +1);
			}
			$query->update($this->_collection,Array("w"=>0));
		}
		
		$this->update_user_count($data["type"],$data["userkey"],$data["name"]);
		
		$this->mongo_db->insert($this->_collection_record, 
			Array("reporter"=>$data["ueid"], 
				"createDate" => $now,
				"target" => $data["key"],
				"type" => $data["type"],
				"userkey" => $data["userkey"],
				"client" => $client ,
				"check" => $check
			));
	}
	
	public function insert_reply($commentID,$url,$comment){ 
		$this->mongo_db->insert($this->_collection_reply,
			Array(
				"createDate" => time() * 1000.0,
				"commentID" => $commentID,
				"url" => $url,
				"content" => $comment,
				"status" => CommentModel::REPLY_WAIT,
				"ip" => get_ip()
			)
		);
	}
	
	
	public function get_reply_waiting(){
		$query = $this->mongo_db->where("status", CommentModel::REPLY_WAIT);
		$comment_replys = $query->get($this->_collection_reply);
		
		$comment_replys_map = Array();
		foreach($comment_replys as $comment_reply){
			if(!isset($comment_replys_map[$comment_reply["commentID"]])){
				$comment_replys_map[$comment_reply["commentID"]] = Array();
			}
			$comment_replys_map[$comment_reply["commentID"]][] = $comment_reply; 
		}
		
		$results = Array();
		foreach($comment_replys_map as $commentID => &$comment_reply){
			$query = $this->mongo_db->where("_id", $commentID);
			$comments = $query->get($this->_collection);
			
			$comment_merged = Array("replys" => $comment_reply);
			if( count($comments) >0){
				$comment_merged["comment"] = $comments[0];
				$comment_result = $this->mongo_db->where(Array("user" => $comments[0]["userkey"]))->get($this->_collection_user);
				if(count($comment_result) <= 0){
					$comment_merged["comment"]["count"] = 0;
				}else{
					$comment_merged["comment"]["count"] = $comment_result[0]["count"];
				}				
			} 
			$results[] = $comment_merged; 
			
		}
		return $results;
	}
	
	public function get_reply($id){
		$mID = new MongoId($id);
		$query = $this->mongo_db->where("_id",$mID);
		$replys = $query->get($this->_collection_reply);
		return count($replys) > 0 ? $replys[0] : null;
	}
	
	public function mark_reply($id,$status,$url_title){
		$mID = new MongoId($id);
		$query = $this->mongo_db->where("_id",$mID);
		$replys = $query->get($this->_collection_reply);
		
		if(count($replys) <= 0){
			return false;
		}

		$reply = $replys[0];
		
		$query = $this->mongo_db
		->where("_id",$mID)
		->set(Array("status" => $status, "modifyDate" => time()*1000.0 ));
		
		if($url_title != null){
			$query->set("url_title" ,$url_title);
		}
		
		$query->update($this->_collection_reply,Array("w"=>0));		
		
		if($status == CommentModel::REPLY_OK){
			$reply["status"] = $status;
			$reply["modifyDate"] = time()*1000.0;

			if($url_title != null){
				$reply["url_title"] = $url_title;
			}
			
			$query = $this->mongo_db->where("_id", $reply["commentID"]);
			$query->set(Array("reply" => $reply,"reply_updated" => time() *1000.0));
			$query->update($this->_collection,Array("w"=>0));
		}
	}
	
	public function removeReply($key){
		$query = $this->mongo_db->where("_id", $key);
		$query->unsetField("reply");
		$query->set(Array("reply_updated" => time() *1000.0));
		$query->update($this->_collection,Array("w"=>0));
	}
	
	public function update_user_count($type,$userkey,$name){
		$now = time() *1000.0;
		
		$exists = $this->mongo_db->where("_id",$type.":".$userkey)->count($this->_collection_user) > 0;
		if(!$exists){
			$this->mongo_db->insert($this->_collection_user,Array("_id" => $type.":".$userkey,"createDate" => $now,"type" => $type,"user" => $userkey));
		}
		
		$bad_count = $this->mongo_db->where(Array(
				"status" => CommentModel::STATUS_BAD,"userkey" => $userkey))->count($this->_collection);
		$check_count = $this->mongo_db->where(Array(
				"status" => CommentModel::STATUS_CHECK,"userkey" => $userkey))->count($this->_collection);
		$wait_count = $this->mongo_db->where(Array(
				"status" => CommentModel::STATUS_WAIT,"userkey" => $userkey))->count($this->_collection);				
		
		$this->mongo_db->set(Array(
				"count" => $bad_count,
				"name" => $name,
				"type" => $type,
				"user" => $userkey,
				"bad_count" => $bad_count,
				"wait_count" => $wait_count,
				"check_count" => $check_count,
				"all_count" => $bad_count + $wait_count + $check_count,
				"last_update" => $now
		));
		$this->mongo_db->where("_id", $type.":".$userkey);
		$this->mongo_db->update($this->_collection_user,Array("w"=>0));
		
	}
	
}