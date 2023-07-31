<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "aircraft_data.php";
	break;
	
	case "new":
		include "aircraft_new.php";
	break;
	
	case "detail":
		include "aircraft_detail.php";
	break;
	
	case "edit":
		include "aircraft_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>