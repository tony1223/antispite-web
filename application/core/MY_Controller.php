<?php use MongoQB\Builder;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class  MY_Controller  extends  CI_Controller  {

	/**
	 * 
	 * @var CommentModel
	 */
	var $commentModel;//CommentModel
	
	/**
	 *
	 * @var UserModel
	 */
	var $userModel;//UserModel
	
	/**
	 *
	 * @var UrlModel
	 */
	var $urlModel;//UrlModel

	public function __construct(){
		parent::__construct();
		session_start();
	}

	protected function _init(){
		session_start();
	}

	protected function return_success_json ($obj = null){
		return $this->return_json(new ReturnMessage(true,0, null, $obj));
	}
	
	protected function return_error($code,$msg){
		return $this->return_json(new ReturnMessage(false,$code,$msg, null));
	}
	
	protected function return_json ($obj){
		echo json_encode($obj);
		return true;
	}
	
}



class ReturnMessage{
	var $isSuccess;
	var $errorCode;
	var $data;
	public function __construct($isSuccess,$errorCode,$errorMessage,$data){
		$this->isSuccess = $isSuccess;
		$this->errorCode = $errorCode;
		$this->errorMessage = $errorMessage;
		$this->data = $data;
	}
}
