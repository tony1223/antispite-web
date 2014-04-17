<?php

function h($string, $quote_style = null, $charset = null, $double_encode = null) {
	return htmlspecialchars($string, $quote_style , $charset, $double_encode );
}

function _date_format($inp_val,$format = "Y-m-d H:i:s"){
	if($inp_val == null || $inp_val=="0000-00-00 00:00:00"){
		return "";
	}
	if(is_string($inp_val)){
		$val = strtotime($inp_val);
		if($val == -1){
			return "invalid date";
		}
	}else{
		$val = $inp_val;
	}

	return date($format,$val);
}

function _date_format_ms($inp_val,$format = "Y-m-d H:i:s"){
	if($inp_val == null || $inp_val=="0000-00-00 00:00:00"){
		return "";
	}
	if(is_string($inp_val)){
		$val = strtotime($inp_val);
		if($val == -1){
			return "invalid date";
		}
	}else{
		$val = $inp_val/1000;
	}

	return date($format,$val);
}

function _display_date_with_fulldate_ms($inp_val,$format = null,$linebreak = "<Br />"){
	if($inp_val == null || $inp_val=="0000-00-00 00:00:00"){
		return "";
	}
	if($format == null){
		 $format = "Y-m-d H:i:s";
	}
	if(is_string($inp_val)){
		$val = strtotime($inp_val);
		if($val == -1){
			return "invalid date";
		}
	}else{
		$val = intval($inp_val / 1000,10);
	}

	return _display_date_with_fulldate($val,$format,$linebreak);
}

function _display_date_with_fulldate($inp_val,$format = "Y-m-d H:i:s",$linebreak = "<Br />"){
	if($inp_val == null || $inp_val=="0000-00-00 00:00:00"){
		return "";
	}
	if(is_string($inp_val)){
		$val = strtotime($inp_val);
		if($val == -1){
			return "invalid date";
		}
	}else{
		$val = $inp_val;
	}
	$diff = time() - $val;
	if ($diff < 0) {
		return date($format,$val);
	} elseif ($diff < 60) {
		return date($format,$val).$linebreak.$diff . ' 秒前';
	} elseif ($diff < 3600) {
		return date($format,$val).$linebreak.floor($diff/60) . ' 分鐘前';
	} elseif ($diff < 86400) {
		return date($format,$val).$linebreak.floor($diff/3600) . ' 小時前';
	} elseif ($diff < 604800) {
		return date($format,$val).$linebreak.floor($diff/86400) . ' 天前';
	} else {
		return date($format,$val);
		//return floor($diff/604800) . '週前';
	}
}
