<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "status_data.php";
	break;
	
	case "new":
		include "status_new.php";
	break;
	
	case "detail":
		include "status_detail.php";
	break;
	
	case "edit":
		include "status_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>