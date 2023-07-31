<?php
/*
 * This class can help you to manage date and time for your php web page.
 *
 * @author : Cokorda Gde Agung Smara Adnyana Putra
 * @email : cokorda.smara@gmail.com
 *
 */

include "timeago.php";

class _Datetime {
 	
	private $default_timezone;
	private $server_date;
	private $server_time;
	
	function __construct() {
		$this->default_timezone = "Asia/Makassar";
		date_default_timezone_set($this->default_timezone);
		
		$this->server_date = date("Y-m-d");
		$this->server_time = date("H:i:s");
	}
	
	function greetings() {
		$hour = date("H");
		
		if ($hour >= 04 and $hour <= 10) {
			return "GOOD MORNING,";
		} elseif ($hour >= 11 and $hour <= 14) {
			return "GOOD AFTERNOON,";
		} elseif ($hour >= 15 and $hour <= 18) {
			return "GOOD EVENING,";
		} elseif ($hour >= 19) {
			return "GOOD NIGHT,";
		} else {
			return "WELCOME,";
		}
	}
	
	function get_number($value) {
		$days = array("7", "1", "2", "3", "4", "5", "6");
		$day = date("w", strtotime($value));
		return $days[$day];
	}
	
	function get_day($value) {
		$days = array("SUNDAY", "MONDAY", "TUESDAY", "WEDNESDAY", "THUSDAY", "FRIDAY", "SATURDAY");
		$day = date("w", strtotime($value));
		return $days[$day];
	}
	
	function server_datetime() {
		return $this->server_date." ".$this->server_time;
	}
	
	function server_date() {
		return $this->server_date;
	}
	
	function server_datetime_add($start, $add) {
		return date("Y-m-d H:i:s", strtotime($add, strtotime($start)));
	}
	
	function database_date($value) {
		return date("Y-m-d", strtotime(str_replace('/', '-', $value)));
	}
	
	function indonesian_date($value) {
		return date("d/m/Y", strtotime($value));
	}
	
	function indonesian_time($value) {
		return date("H:i:s", strtotime($value));
	}
	
	function indonesian_datetime($value) {
		return date("d/m/Y H:i:s", strtotime($value));
	}
	
	function time_ago($timestring) {
	 	$timeAgo = new TimeAgo($this->default_timezone);
	  	
	 	if (!empty($timestring)) {
	  		return $timeAgo->inWords($timestring, "now");
	  	} else {
	  		return "not logged in yet";
	  	}
	}
}

$datetime = new _Datetime;
?>