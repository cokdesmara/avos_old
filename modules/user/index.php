<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "user_data.php";
	break;
	
	case "new":
		include "user_new.php";
	break;
	
	case "detail":
		include "user_detail.php";
	break;
	
	case "edit":
		include "user_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>