<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "profile_detail.php";
	break;
	
	case "edit":
		include "profile_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>