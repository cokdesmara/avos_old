<?php
include "../../includes/configuration.php";

if (empty($_SESSION["user_session"])) {
	header("location:../../index.php"); 
} else {
	include "../../includes/connection.php";
	include "../../includes/secure.php";
	include "../../includes/uri.php";
	include "../../includes/datetime.php";

	$page = $_GET["page"];
	$act = $_GET["act"];
	
	if ($page == "account" and $act == "update") {
		$id = $secure->sanitize($_POST["id"]);
		$name = strtoupper($secure->sanitize($_POST["name"]));
		$email = strtoupper($secure->sanitize($_POST["email"]));
		$password = md5(strtoupper($secure->sanitize($_POST["password"])));
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		if (empty($_POST["password"])) {
			$update = $mysqli->query("update t_user set name = '".$name."',
										   	  			user = '".$user."',
										      			modified = '".$modified."'
		                             			  where id = '".$id."'");
		} else {
		  	$update = $mysqli->query("update t_user set name = '".$name."',
										   	  			password = '".$password."',
										   	  			user = '".$user."',
										   	  			modified = '".$modified."'
		                             			  where id = '".$id."'");
		}
		
		if (!$update) {
			$_SESSION["account"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
			header("location:../../index.php?page=".$page."&act=edit&id=".$id);
		} else {
			$_SESSION["account"] = "YOUR ACCOUNT WITH E-MAIL <b>".$email."</b> HAS BEEN SUCCESSFULLY UPDATED !";
			header("location:../../index.php?page=".$page);
		}
	}
}
?>