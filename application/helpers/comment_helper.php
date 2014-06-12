<?php 
function comment_user_link($comment,$key = "userkey"){
	
	if($comment["type"] =="FBComment"){
		if(strpos($comment[$key],"yahoo:") === 0){
			return null;
		}else if(strpos($comment[$key],"id=") !== False){
			return "https://www.facebook.com/profile.php?".$comment[$key];
		}else{
			return "https://www.facebook.com/".$comment[$key];
		}
	}else if($comment["type"] == "YahooComment"){
		return "https://profile.yahoo.com/".$comment[$key];
	}
	return "";
}

function is_login(){
	return isset($_SESSION["suser"]);
}

function get_ip(){
	$ip = null;
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function get_comment_type_description($type){
	if($type == "FBComment"){
		return "FB 留言板";
	}
	if($type == "YahooComment"){
		return "Yahoo 留言板";
	}
	return "";
}

?>