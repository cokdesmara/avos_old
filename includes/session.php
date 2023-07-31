<?php
/*
 * This function can help you to validate login session in your php web page.
 *
 * @author : Cokorda Gde Agung Smara Adnyana Putra
 * @email : cokorda.smara@gmail.com
 *
 */
 
class _Session {
	function session_validate() {
		global $mysqli;
		
		if (!empty($_SESSION["user_session"])) {
			$query = $mysqli->query("select t_user.active as active, t_user.session as session from t_user where t_user.id = '".$_SESSION["user_id"]."'");
			$r = $query->fetch_assoc();
			
			if ($r["active"] == "Y") {
				$old_session = $_SESSION["user_session"];
				$new_session = $r["session"];
				
				if ($old_session != $new_session) {
					return "offline";
				} else {
					return "online";
				}
			} else {
				return "deactive";
			}
		}
	}
}

$session = new _Session;
?>