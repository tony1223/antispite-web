<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author TonyQ
 *
 */
class UserModel extends MONGO_MODEL {
	var $_collection = "users";
	function __construct()
	{
		parent::__construct();
	}
	
	public function insert($account,$pwd){
		$check= $this->mongo_db->where(Array("account"=> $account))->count($this->_collection);
		
		if($check > 0 ){
			return false;
		}
		$this->mongo_db->insert($this->_collection,Array("enabled" => false,"account"=> $account,"password" => sha1($pwd)));

		return true;
	}
	
	public function find_valid_user($account,$pwd){
		$users= $this->mongo_db->where(Array("enabled" => true,"account"=> $account,"password" => sha1($pwd)))->get($this->_collection);
		
		if(count($users) == 0 || count($users) > 1){
			return null;
		}
		return $users[0];
		
	}

}
