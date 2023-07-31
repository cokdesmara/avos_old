<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "production_data.php";
	break;
	
	case "detail":
		include "production_detail.php";
	break;
	
	case "edit":
		include "production_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>