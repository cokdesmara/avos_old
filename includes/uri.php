<?php
/*
 * This class can help you to manage uri request in your php web page.
 *
 * @author : Cokorda Gde Agung Smara Adnyana Putra
 * @email : cokorda.smara@gmail.com
 *
 */
 
class _Uri {
	function request($value) {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			return $_POST[$value];
		} else {
			return $_GET[$value];
		}
	}
	
	function json($json, $istr="  ") {
	    $result = "";
	    for($p=$q=$i=0; isset($json[$p]); $p++)
	    {
	        $json[$p] == '"' && ($p>0?$json[$p-1]:"") != "\\" && $q=!$q;
	        if(strchr("}]", $json[$p]) && !$q && $i--)
	        {
	            strchr("{[", $json[$p-1]) || $result .= "\n".str_repeat($istr, $i);
	        }
	        $result .= $json[$p];
	        if(strchr(",{[", $json[$p]) && !$q)
	        {
	            $i += strchr("{[", $json[$p]) === FALSE ? 0 : 1;
	            strchr("}]", $json[$p+1]) || $result .= "\n".str_repeat($istr, $i);
	        }
	    }
	    
	    header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: Tue, 01 Jan 1980 1:00:00 GMT");
		header("Content-type: application/json; charset=UTF-8");
	    return $result;
	}
	
	function base_url() {
		$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5)) == "https://" ? "https://" : "http://";
		$hostName = $_SERVER["HTTP_HOST"];
		$url = $_SERVER["REQUEST_URI"];
		$urlParse = parse_url($url);
		$path = explode("/", $urlParse["path"]);
		if (IS_PATH === true) {
			return $protocol.$hostName."/".$path[1];
		} else {
			return $protocol.$hostName;
		}
	}
}

$uri = new _Uri;
?>