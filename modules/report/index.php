<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["act"]) {
	case "":
		include "errors/404.php";
	break;
	
	case "mealorderpob":
		if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER" or $_SESSION["user_privilege"] == "MONITORING") {
			include "report_mealorderpob.php";
		} else {
			include "errors/401.php";
		}
	break;
	
	case "production":
		if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "PRODUCTION" or $_SESSION["user_privilege"] == "MONITORING") {
			include "report_production.php";
		} else {
			include "errors/401.php";
		}
	break;
	
	case "mealuplift":
		if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "OPERATION GA" or $_SESSION["user_privilege"] == "OPERATION NONGA" or $_SESSION["user_privilege"] == "MONITORING") {
			include "report_mealuplift.php";
		} else {
			include "errors/401.php";
		}
	break;
	
	case "oversupply":
		if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "MONITORING") {
			include "report_oversupply.php";
		} else {
			include "errors/401.php";
		}
	break;
	
	case "overproduction":
		if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "MONITORING") {
			include "report_overproduction.php";
		} else {
			include "errors/401.php";
		}
	break;
	
	case "wastedmeal":
		if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER" or $_SESSION["user_privilege"] == "MONITORING") {
			include "report_wastedmeal.php";
		} else {
			include "errors/401.php";
		}
	break;
	
	case "overcost":
		if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "MONITORING") {
			include "report_overcost.php";
		} else {
			include "errors/401.php";
		}
	break;
	
	case "loadfactor":
		if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER" or $_SESSION["user_privilege"] == "MONITORING") {
			include "report_loadfactor.php";
		} else {
			include "errors/401.php";
		}
	break;
	
	default:
		include "errors/404.php";
	break;
}
?>