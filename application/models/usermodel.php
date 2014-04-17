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
	
	
	public function find_user($account,$pwd){
		$users= $this->mongo_db->where(Array("account"=> $account,"password" => sha1($pwd)))->get($this->_collection);
		
		if(count($users) == 0 || count($users) > 1){
			return null;
		}
		return $users[0];
		
	}

}
