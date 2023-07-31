<?php
include "../../includes/configuration.php";

if (empty($_SESSION["user_session"])) {
	header("location:../../index.php"); 
} else {
	include "../../includes/connection.php";
	include "../../includes/secure.php";
	include "../../includes/datetime.php";

	$page = $_GET["page"];
	$act = $_GET["act"];
	
	if ($page == "profile" and $act == "update") {
		$id = $secure->sanitize($_POST["id"]);
		$company = strtoupper($secure->sanitize($_POST["company"]));
		$branch = strtoupper($secure->sanitize($_POST["branch"]));
		$address = strtoupper($secure->sanitize($_POST["address"]));
		$phone = strtoupper($secure->sanitize($_POST["phone"]));
		$fax = strtoupper($secure->sanitize($_POST["fax"]));
		$email = strtoupper($secure->sanitize($_POST["email"]));
		$website = strtoupper($secure->sanitize($_POST["website"]));
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		$update = $mysqli->query("update t_profile set company = '".$company."',
			                              		       branch = '".$branch."',
					                                   address = '".$address."',
													   phone = '".$phone."',
													   fax = '".$fax."',
													   email = '".$email."',
													   website = '".$website."',
													   user = '".$user."',
													   modified = '".$modified."'
		                            			 where id = '".$id."'");
		
		if (!$update) {
			$_SESSION["profile"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
			header("location:../../index.php?page=".$page."&act=edit");
		} else {
			$_SESSION["profile"] = "COMPANY PROFILE HAS BEEN SUCCESSFULLY UPDATED !";
			header("location:../../index.php?page=".$page);
		}
	}
}
?>