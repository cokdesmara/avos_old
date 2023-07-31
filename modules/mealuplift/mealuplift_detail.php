<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/mealuplift/mealuplift_action.php";
$date = $secure->sanitize($_GET["date"]);
$airline = $secure->sanitize($_GET["airline"]);

$query = $mysqli->query("select t_header.date as date from t_header where t_header.date = '".$datetime->database_date($date)."' group by t_header.date");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("MEAL UPLIFT DATA", "index.php?page=mealuplift");
	$breadcrumb->append_crumb("DETAIL MEAL UPLIFT", "#");
	echo $breadcrumb->breadcrumb();
	
	$query_user = $mysqli->query("select t_user.name as user, t_meal_uplift.modified as modified from t_meal_uplift left join t_header on t_meal_uplift.header = t_header.id left join t_flight on t_header.flight = t_flight.id left join t_user on t_meal_uplift.user = t_user.id where t_header.date = '".$datetime->database_date($date)."' and if(t_flight.airline = 1 or t_flight.airline = 31, 'GA', 'NONGA') = '".strtoupper($airline)."' order by t_meal_uplift.modified desc limit 0, 1");
	$u = $query_user->fetch_assoc();
	
	$day = $datetime->get_day($r["date"]);
	$modified = !empty($u["modified"]) ? $datetime->indonesian_datetime($u["modified"]) : "-";
	$user = !empty($u["user"]) ? $u["user"] : "-";
	
	echo "<div class='panel'>
			<div class='panel-label'>MEAL UPLIFT ENTRY</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>DETAIL FINAL MEAL UPLIFT</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$user."</div>
				</div>
				<div class='separator'></div>
				
		  		<div class='form-horizontal'>
		  			<table class='field-table'>
						<tr>
							<th><label>DATE</label></th>
							<td style='width:20px;'>:</td>
							<td>".$day.", ".$date."</td>
						</tr>
						<tr>
							<th><label>AIRLINE</label></th>
							<td>:</td>
							<td>".strtoupper($airline == "nonga" ? "non ga" : "ga")."</td>
						</tr>
						<tr>
	                        <td colspan='3'><div class='separator'></div></td>
	                    </tr>
					</table>
					
            		<div class='pull-left'>
            			<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=mealuplift';\"><i class='icon-arrow-left icon-white'></i> BACK</button>";
						if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "OPERATION GA" or $_SESSION["user_privilege"] == "OPERATION NONGA") {
                  			echo "&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success' onclick=\"window.location.href='index.php?page=mealuplift&act=edit&date=".$date."&airline=".$airline."';\"><i class='icon-edit icon-white'></i> EDIT</button>";
						}
					echo "</div>
					
					<table id='dt-mealuplift-detail' class='table table-bordered table-condensed table-striped table-hover'>
						<thead>
							<tr>
								<th class='span1 text-center no-sort no-search'>NO</th>
								<th class='span1 text-center no-visible no-search'>ID</th>
								<th class='span1 text-center no-visible no-search'>ID</th>
							  	<th class='text-center' style='width:150px;'>FLIGHT</th>
							  	<th class='text-center' style='width:120px;'>F/C</th>
								<th class='text-center' style='width:120px;'>B/C</th>
								<th class='text-center' style='width:120px;'>Y/C</th>
								<th class='text-center' style='width:120px;'>C/R</th>
								<th class='text-center' style='width:120px;'>C/P</th>
								<th class='text-center' style='width:100px;'>REMARK</th>
							</tr>
						</thead>
						
						<tfoot>
							<tr>
								<th class='span1 text-center'>NO</th>
								<th class='span1'>ID</th>
								<th class='span1'>ID</th>
							  	<th class='text-center' style='width:150px;'>FLIGHT</th>
							  	<th class='text-center' style='width:120px;'>F/C</th>
								<th class='text-center' style='width:120px;'>B/C</th>
								<th class='text-center' style='width:120px;'>Y/C</th>
								<th class='text-center' style='width:120px;'>C/R</th>
								<th class='text-center' style='width:120px;'>C/P</th>
								<th class='text-center' style='width:100px;'>REMARK</th>
							</tr>
						</tfoot>
				 	</table>
				</div>
				
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>