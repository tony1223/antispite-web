<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * @SWG\Resource(
 *     apiVersion="1.0",
 *     swaggerVersion="1.2",
 *     resourcePath="/url",
 *     basePath="http://antispite.tonyq.org/api/url"
 *
 * )
*/
class Url extends MY_Controller {
	/**
	 *
	 * @SWG\Api(
	 *   path="/index",
	 *   description="取得所有跳針相關網址列表",
	 *   @SWG\Operation(
	 *   	method="GET", summary="取得跳針相關網址列表，注意：不一定有 title。",
	 *   	@SWG\Parameters(
	 *   		@SWG\Parameter(
	 *          	name="order",
	 *           	description="'hot'(從跳針數多到少) 或 'recent' (時間從近到久)，預設是 recent。",
	 *           	paramType="query",
	 *           required=false,
	 *           type="string"
	 *         )
	 *      )
	 *   )
	 * )
	 */
	public function index(){
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json; charset=utf-8');
		$this->load->model("urlModel");
		
		$order = $this->input->get("order");
		if($order != "hot"){
			$order = "recent";
		} 
		
		$urls = $this->urlModel->get_urls_for_api($order);
		foreach($urls as &$url){
			$url["url"] = $url["_id"];
			unset($url["_id"]); 
		}
	
		echo json_encode($urls);
	}
	
	/**
	 *
	 * @SWG\Api(
	 *   path="/view",
	 *   description="取得特定網址底下的跳針留言",
	 *   @SWG\Operation(
	 *   	method="GET", summary="取得特定網址的跳針使用者相關跳針留言清單。注意不一定會有 url_title/reply。(createDate 在 FB 是發言時間，yahoo 則是紀錄時間(拿不到發言時間..QQ))",
	 *   	@SWG\Parameters(
	 *   		@SWG\Parameter(
	 *          	name="url",
	 *           	description="網址(完整網址，包含 http:// 跟 https://，可參考 /index 的 url 資料)",
	 *           	paramType="query",
	 *           required=true,
	 *           type="string"
	 *         )
	 *      )
	 *   )
	 * )
	 */
	public function view(){
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json; charset=utf-8');
		$url = $this->input->get("url");
		$this->load->model("commentModel");
		$this->load->model("urlModel");
		$url_item = $this->urlModel->get($url);
	
		$comments = $this->commentModel->get_bads_by_url($url);
	
		if($url_item == null){
			$title = $url;
		}else{
			$title = isset($url_item["title"]) ? $url_item["title"] : $url_item["_id"];
		}
	
		foreach($comments as &$comment){
			// 		"type": "FBComment",
			// 		"name": "Henry Chung",
			// 		"userkey": "henry.chung.794",
			// 		"content": "吳緯宸 你從事丟臉的行業？",
			// 		"time": 1399035866000,
			// 		"key": "fbc_859799560702110_859903167358416_859903167358416_reply",
			// 		"url": "http://www.appledaily.com.tw/realtimenews/article/new/20140502/390501/",
			// 		"url_title": "太陽花學運女王遭控歛財劉喬安坦承有瑕疵 | 即時新聞 | 20140502 | 蘋果日報",			
			$comment["userid"] = $comment["userkey"];
			$comment["createDate"] = $comment["time"];
			unset($comment["time"]);
			unset($comment["userkey"]);
		}
	
		echo json_encode($comments);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */