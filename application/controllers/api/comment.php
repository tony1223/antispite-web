<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * @SWG\Resource(
 *     apiVersion="1.0",
 *     swaggerVersion="1.2",
 *     resourcePath="/comment",
 *     basePath="http://antispite.tonyq.org/api/comment"
 *
 * )
*/
class Comment extends MY_Controller {


	/**
	 *
	 * @SWG\Api(
	 *   path="/rank",
	 *   description="取得跳針使用者清單",
	 *   @SWG\Operation(
	 *   	method="GET", summary="取得所有跳針數超過10 的，以跳針數排序。"
	 *   )
	 * )
	 */
	public function rank(){
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json; charset=utf-8');

		$this->load->model("commentModel");

		$users = $this->commentModel->get_ranked_users(10);
		if(count($users) == 0){
			return show_404();
		}
		foreach($users as &$user){
			$user["userID"] = $user["user"];
			$user["userLink"] = comment_user_link($user,"userID");
			unset( $user["_id"]);
			unset( $user["user"]);
		}
		echo json_encode($users);

	}

	/**
	 *
	 * @SWG\Api(
	 *   path="/user",
	 *   description="取得跳針使用者跳針留言清單。注意不一定會有 url_title/reply。",
	 *   @SWG\Operation(
	 *   	method="GET", summary="取得該使用者所有跳針評論 (createDate 在 FB 是發言時間，yahoo 則是紀錄時間(拿不到發言時間..QQ))",
	 *   	@SWG\Parameters(
	 *			@SWG\Parameter(
	 *          	name="userID",
	 *           	description="使用者 ID，可以從 rank 或其他 api 取得",
	 *           	paramType="query",
	 *           required=true,
	 *           type="string"
	 *         )
	 *      )
	 *   )
	 * )
	 */
	public function user(){
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json; charset=utf-8');
		$key = $this->input->get("userID");
		$this->load->model("commentModel");
		
		$comments = $this->commentModel->get_bads_by_user_all($key);
		foreach($comments as &$comment){
			$comment["userid"] = $comment["userkey"];
			$comment["createDate"] = $comment["time"];
			unset($comment["userkey"]);
			unset($comment["time"]);
		}
		echo json_encode($comments);
	}
	
	
	/**
	 *
	 * @SWG\Api(
	 *   path="/recent",
	 *   description="取得最近兩百筆跳針留言清單",
	 *   @SWG\Operation(
	 *   	method="GET", summary="取得最近兩百筆跳針留言清單。(createDate 在 FB 是發言時間，yahoo 則是紀錄時間(拿不到發言時間..QQ))",
	 *   	@SWG\Parameters(
	 *      )
	 *   )
	 * )
	 */

	public function recent(){
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json; charset=utf-8');
		$key = $this->input->get("userID");
		$this->load->model("commentModel");
	
		$comments = $this->commentModel->get_recent_bads();
		foreach($comments as &$comment){
			$comment["userid"] = $comment["userkey"];
			$comment["createDate"] = $comment["time"];
			unset($comment["userkey"]);
			unset($comment["time"]);
			//$comments["userID"] = $comments["_id"];
		}
		echo json_encode($comments);
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */