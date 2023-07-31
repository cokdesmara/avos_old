<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "account_detail.php";
	break;
	
	case "edit":
		include "account_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>