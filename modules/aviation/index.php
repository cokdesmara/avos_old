<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "aviation_data.php";
	break;
	
	case "new":
		include "aviation_new.php";
	break;
	
	case "detail":
		include "aviation_detail.php";
	break;
	
	case "edit":
		include "aviation_edit.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>