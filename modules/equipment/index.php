<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "equipment_data.php";
	break;
	
	case "new":
		include "equipment_new.php";
	break;
	
	case "detail":
		include "equipment_detail.php";
	break;
	
	case "edit":
		include "equipment_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>