<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "loadfactor_data.php";
	break;
	
	case "detail":
		include "loadfactor_detail.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>