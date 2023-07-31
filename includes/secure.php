<?php
/*
 * This class can help you to secure your php web page.
 *
 * @author : Cokorda Gde Agung Smara Adnyana Putra
 * @email : cokorda.smara@gmail.com
 *
 */
 
class _Secure {
	private $filter;
	
	function sanitize($data){
		global $mysqli;
		$this->filter = $mysqli->real_escape_string(stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES))));
	  	return $this->filter;
	}
}

$secure = new _Secure;
?>