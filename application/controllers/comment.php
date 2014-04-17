<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends MY_Controller {

	public function report(){
		header("Access-Control-Allow-Origin: https://www.facebook.com");
		header('Content-Type: application/json; charset=utf-8');
		
		$data = json_decode($this->input->post("data"));
		die(var_dump($data));
		return $this->return_success_json();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */