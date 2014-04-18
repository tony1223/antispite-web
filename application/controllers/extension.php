<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extension extends MY_Controller {
	
	public function download(){
		$this->load->view('extension/download',Array(
			"pageTitle" => "跳針留言小幫手" ,
			"selector" => "download"
		));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */