<?php 
function comment_user_link($comment){
	
	if($comment["type"] =="FBComment"){
		if(strpos($comment["userkey"],"id=") !== False){
			return "https://www.facebook.com/profile.php?".$comment["userkey"];
		}else{
			return "https://www.facebook.com/".$comment["userkey"];
		}
	}
	return "";
}

function is_login(){
	return isset($_SESSION["suser"]);
}
?>