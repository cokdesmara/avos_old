<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "flightmeal_data.php";
	break;
	
	case "detail":
		include "flightmeal_detail.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>