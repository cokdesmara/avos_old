<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

include "includes/datetime.php";
include "includes/breadcrumb.php";
include "includes/pagination.php";

$session = $session->session_validate();

if ($session == "online") {
	switch($_GET["page"]) {
		case "dashboard":
			include "modules/dashboard/index.php";
		break;
		
		case "account":
			include "modules/account/index.php";
		break;
		
		case "user":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR") {
				include "modules/user/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "aviation":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				include "modules/aviation/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "aircraft":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				include "modules/aircraft/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "airline":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				include "modules/airline/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "equipment":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				include "modules/equipment/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "flight":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				include "modules/flight/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "config":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				include "modules/config/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "status":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				include "modules/status/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "mealorderpob":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER" or $_SESSION["user_privilege"] == "MONITORING") {
				include "modules/mealorderpob/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "production":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "PRODUCTION" or $_SESSION["user_privilege"] == "MONITORING") {
				include "modules/production/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "mealuplift":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "OPERATION GA" or $_SESSION["user_privilege"] == "OPERATION NONGA" or $_SESSION["user_privilege"] == "MONITORING") {
				include "modules/mealuplift/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "flightmeal":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "MONITORING") {
				include "modules/flightmeal/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "overcost":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "MONITORING") {
				include "modules/overcost/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "loadfactor":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER" or $_SESSION["user_privilege"] == "MONITORING") {
				include "modules/loadfactor/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "report":
			include "modules/report/index.php";
		break;
		
		case "profile":
			include "modules/profile/index.php";
		break;
		
		case "log":
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "MONITORING") {
				include "modules/log/index.php";
			} else {
				include "errors/401.php";
			}
		break;
		
		case "":
			include "modules/dashboard/index.php";
		break;
		
		default:
			include "errors/404.php";
		break;
	}
} elseif ($session == "offline") {
	include "errors/401.php";
	
	echo "<div id='session' class='modal hide fade message' tabindex='-1' role='dialog' aria-hidden='true'>
		  	<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4>ATTENTION</h4>
			</div>
			<div class='modal-body'>
				<div id='message'></div>
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-success' data-dismiss='modal' aria-hidden='true'><i class='icon-ok icon-white'></i> OK</button>
			</div>
	   	</div>
	   	
	   	<script>
			$('#message').html('<center>YOUR LOGIN SESSION IS OVER !<br/>PLEASE TRY TO LOGIN AGAIN.</center>');
			$('#session').modal('show').on('hidden', function () {
				window.location.href='logout.php'
			});
		</script>";
} elseif ($session == "deactive") {
	include "errors/401.php";
	
	echo "<div id='session' class='modal hide fade message' tabindex='-1' role='dialog' aria-hidden='true'>
		  	<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4>ATTENTION</h4>
			</div>
			<div class='modal-body'>
				<div id='message'></div>
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-success' data-dismiss='modal' aria-hidden='true'><i class='icon-ok icon-white'></i> OK</button>
			</div>
	   	</div>
	   	
	   	<script>
			$('#message').html('<center>USER IS NOT ACTIVE !</center>');
			$('#session').modal('show').on('hidden', function () {
				window.location.href='logout.php'
			});
		</script>";
} else {
	include "errors/401.php";
	
	echo "<div id='session' class='modal hide fade message' tabindex='-1' role='dialog' aria-hidden='true'>
		  	<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4>ATTENTION</h4>
			</div>
			<div class='modal-body'>
				<div id='message'></div>
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-success' data-dismiss='modal' aria-hidden='true'><i class='icon-ok icon-white'></i> OK</button>
			</div>
	   	</div>
	   	
	   	<script>
			$('#message').html('<center>YOU HAVE BEEN LOGGED OUT !</center>');
			$('#session').modal('show').on('hidden', function () {
				window.location.href='logout.php'
			});
		</script>";
}
?>