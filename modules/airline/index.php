<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "airline_data.php";
	break;
	
	case "new":
		include "airline_new.php";
	break;
	
	case "detail":
		include "airline_detail.php";
	break;
	
	case "edit":
		include "airline_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>