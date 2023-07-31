<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "mealuplift_data.php";
	break;
	
	case "detail":
		include "mealuplift_detail.php";
	break;
	
	case "edit":
		include "mealuplift_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>