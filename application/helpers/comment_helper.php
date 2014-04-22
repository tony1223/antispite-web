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
	}
	return "";
}

function is_login(){
	return isset($_SESSION["suser"]);
}
?>