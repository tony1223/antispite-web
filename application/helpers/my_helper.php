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


/**
 * Returns the values from a single column of the input array, identified by
 * the $columnKey.
 *
 * Optionally, you may provide an $indexKey to index the values in the returned
 * array by the values from the $indexKey column in the input array.
 *
 * @param array $input A multi-dimensional array (record set) from which to pull
 *                     a column of values.
 * @param mixed $columnKey The column of values to return. This value may be the
 *                         integer key of the column you wish to retrieve, or it
 *                         may be the string key name for an associative array.
 * @param mixed $indexKey (Optional.) The column to use as the index/keys for
 *                        the returned array. This value may be the integer key
 *                        of the column, or it may be the string key name.
 * @return array
 */
function array_column($input = null, $columnKey = null, $indexKey = null)
{
	// Using func_get_args() in order to check for proper number of
	// parameters and trigger errors exactly as the built-in array_column()
	// does in PHP 5.5.
	$argc = func_num_args();
	$params = func_get_args();

	if ($argc < 2) {
		trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
		return null;
	}

	if (!is_array($params[0])) {
		trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);
		return null;
	}

	if (!is_int($params[1])
	&& !is_float($params[1])
	&& !is_string($params[1])
	&& $params[1] !== null
	&& !(is_object($params[1]) && method_exists($params[1], '__toString'))
	) {
		trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
		return false;
	}

	if (isset($params[2])
	&& !is_int($params[2])
	&& !is_float($params[2])
	&& !is_string($params[2])
	&& !(is_object($params[2]) && method_exists($params[2], '__toString'))
	) {
		trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
		return false;
	}

	$paramsInput = $params[0];
	$paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;

	$paramsIndexKey = null;
	if (isset($params[2])) {
		if (is_float($params[2]) || is_int($params[2])) {
			$paramsIndexKey = (int) $params[2];
		} else {
			$paramsIndexKey = (string) $params[2];
		}
	}

	$resultArray = array();

	foreach ($paramsInput as $row) {

		$key = $value = null;
		$keySet = $valueSet = false;

		if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
			$keySet = true;
			$key = (string) $row[$paramsIndexKey];
		}

		if ($paramsColumnKey === null) {
			$valueSet = true;
			$value = $row;
		} elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
			$valueSet = true;
			$value = $row[$paramsColumnKey];
		}

		if ($valueSet) {
			if ($keySet) {
				$resultArray[$key] = $value;
			} else {
				$resultArray[] = $value;
			}
		}

	}

	return $resultArray;
}

function startsWith($haystack, $needle)
{
	return !strncmp($haystack, $needle, strlen($needle));
}


