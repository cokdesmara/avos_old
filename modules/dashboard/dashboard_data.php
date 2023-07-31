<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/dashboard/dashboard_action.php";
$from = !empty($_GET["from"]) ? $secure->sanitize($_GET["from"]) : date("01/m/Y");
$to = !empty($_GET["to"]) ? $secure->sanitize($_GET["to"]) : date("t/m/Y");

$breadcrumb->append_crumb("DASHBOARD", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
	  	<div class='panel-label'>AVOS DASHBOARD</div>
		<div class='panel-page'>";
			
			$login = strtoupper($datetime->time_ago($_SESSION["user_login"]));
		  	$time = $datetime->indonesian_datetime($_SESSION["user_login"]);
			
	    	echo "<div class='alert alert-success'><center>".$datetime->greetings()." <strong>".$_SESSION["user_name"]."</strong>. YOU LOGIN AS <strong>".$_SESSION["user_privilege"]." USER</strong> ".$login." AT ".$time."</center></div>
			<div class='separator'></div>
			
			<div class='panel-action clearfix'>
				<form class='form-inline form-search pull-right' method='GET' action='".$_SERVER["PHP_SELF"]."'>
			  		<input type='hidden' name='page' value='dashboard'>
			  		<label class='control-label' for='from'>FROM : </label>&nbsp;
					<div id='dp1' class='input-append date datepicker'>
						<input type='text' id='from' name='from' class='input-small' value='".$from."' /><span class='add-on'><i class='icon-calendar'></i></span>
					</div>&nbsp;
					<label class='control-label' for='to'>TO : </label>&nbsp;
					<div id='dp2' class='input-append date datepicker'>
						<input type='text' id='to' name='to' class='input-small' value='".$to."' /><span class='add-on'><i class='icon-calendar'></i></span>
					</div>&nbsp;
					<button type='submit' class='btn btn-success'><i class='icon-search icon-white'></i> FIND</button>
			  	</form>
		  	</div>
			<div class='separator'></div>

			<iframe id='chart' class='chart' src='".$action."?page=dashboard&act=chart&from=".$from."&to=".$to."' frameborder='0' scrolling='no'></iframe>
			<div class='clear-fix'></div>
			
		</div>
	</div>";

$redundant = $mysqli->query("delete t_header from t_header left join t_meal_order_pob on t_header.id = t_meal_order_pob.header left join t_production on t_header.id = t_production.header left join t_meal_uplift on t_header.id = t_meal_uplift.header where t_meal_order_pob.id is null and t_production.id is null and t_meal_uplift.id is null");
?>
