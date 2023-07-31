<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "mealorderpob_data.php";
	break;
	
	case "new":
		include "mealorderpob_new.php";
	break;
	
	case "detail":
		include "mealorderpob_detail.php";
	break;
	
	case "edit":
		include "mealorderpob_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>