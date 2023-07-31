<?php
include "includes/configuration.php";
include "includes/connection.php";
include "includes/secure.php";
include "includes/uri.php";
include "includes/datetime.php";

$email = strtoupper($secure->sanitize($uri->request("email")));
$password = md5(strtoupper($secure->sanitize($uri->request("password"))));

if (empty($email) or empty($password)) {
	print("empty");
} else {
	$query = $mysqli->query("select t_user.id as id, t_user.name as name, t_user.email as email, t_user.privilege as privilege, t_user.active as active from t_user where t_user.email = '".$email."' and t_user.password = '".$password."'");
	$row = $query->num_rows;
	$r = $query->fetch_assoc();
	
	if ($row > 0) {
		if ($r["active"] == "Y") {
		  	$_SESSION["user_id"] = $r["id"];
		  	$_SESSION["user_name"] = $r["name"];
			$_SESSION["user_email"] = $r["email"];
			$_SESSION["user_privilege"] = $r["privilege"];
			$id = $_SESSION["user_id"];
			$login = $datetime->server_datetime();
			
			$old_session = session_id();
			session_regenerate_id();
			$new_session = session_id();
			
		  	$mysqli->query("update t_user set login = '".$login."', session = '".$new_session."' where id = '".$id."'");
			$user = $mysqli->query("select t_user.login as login, t_user.session as session from t_user where t_user.id = '".$id."'");
			$u = $user->fetch_assoc();
			$_SESSION["user_login"] = $u["login"];
			$_SESSION["user_session"] = $u["session"];
			$mysqli->query("insert into t_log (user,login,session) values ('".$id."','".$u["login"]."','".$u["session"]."')");
			
			print("login");
		} else {
			print("deactive");
		}
	} else {
		print("wrong");
	}
}
?>
