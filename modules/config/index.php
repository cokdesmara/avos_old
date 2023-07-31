<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "config_data.php";
	break;
	
	case "new":
		include "config_new.php";
	break;
	
	case "detail":
		include "config_detail.php";
	break;
	
	case "edit":
		include "config_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>