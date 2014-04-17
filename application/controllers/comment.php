<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends MY_Controller {
	public function report(){
		header("Access-Control-Allow-Origin: https://www.facebook.com");
		header('Content-Type: application/json; charset=utf-8');
		
		$data = json_decode($this->input->post("data"));
		
		/*
		 * {
              type:"FBComment",
              name:username,
              userkey:userkey,
              content:content,
              time:time,
              key:key,
              url:url,
              ueid:chrome.runtime.id
            }
		 */
		
		if($data == null){
			return $this->return_error(400,"parameter not correct.");
		}
		$keys = Array("type","name","userkey","content","time","key","url");
		
		$inserting_data = Array();
		foreach ($keys as $key){
			if(!isset($data->$key)){
				return $this->return_error(400,"parameter not correct.");
			}
			$inserting_data[$key] = $data->$key;
		}
		
		$ueid = $this->input->post("ueid");
		if($ueid == ""){
			return $this->return_error(400,"parameter not correct.");
		}
		$inserting_data["ueid"] = $ueid;
		
		$inserting_data["_id"] = $data->key;
		$this->load->model("commentModel");
		$this->commentModel->insert($inserting_data);
		return $this->return_success_json();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */