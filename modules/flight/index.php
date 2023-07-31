<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "flight_data.php";
	break;
	
	case "new":
		include "flight_new.php";
	break;
	
	case "detail":
		include "flight_detail.php";
	break;
	
	case "edit":
		include "flight_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>