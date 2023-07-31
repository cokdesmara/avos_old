<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "log_data.php";
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>