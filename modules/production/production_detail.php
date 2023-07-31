<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/production/production_action.php";
$date = $secure->sanitize($_GET["date"]);

$query = $mysqli->query("select t_header.date as date from t_header where t_header.date = '".$datetime->database_date($date)."' group by t_header.date");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("PRODUCTION DATA", "index.php?page=production");
	$breadcrumb->append_crumb("DETAIL PRODUCTION", "#");
	echo $breadcrumb->breadcrumb();
	
	$query_user = $mysqli->query("select t_user.name as user, t_production.modified as modified from t_production left join t_header on t_production.header = t_header.id left join t_user on t_production.user = t_user.id where t_header.date = '".$datetime->database_date($date)."' order by t_production.modified desc limit 0, 1");
	$u = $query_user->fetch_assoc();
	
	$day = $datetime->get_day($r["date"]);
	$modified = !empty($u["modified"]) ? $datetime->indonesian_datetime($u["modified"]) : "-";
	$user = !empty($u["user"]) ? $u["user"] : "-";
	
	echo "<div class='panel'>
			<div class='panel-label'>PRODUCTION ENTRY</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>DETAIL FINAL PRODUCTION</div>
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
	                        <td colspan='3'><div class='separator'></div></td>
	                    </tr>
					</table>
					
            		<div class='pull-left'>
            			<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=production';\"><i class='icon-arrow-left icon-white'></i> BACK</button>";
						if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "PRODUCTION") {
                  			echo "&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success' onclick=\"window.location.href='index.php?page=production&act=edit&date=".$date."';\"><i class='icon-edit icon-white'></i> EDIT</button>";
						}
					echo "</div>
					
					<table id='dt-production-detail' class='table table-bordered table-condensed table-striped table-hover'>
						<thead>
							<tr>
						  		<th rowspan='2' class='span1 text-center no-sort no-search'>NO</th>
								<th rowspan='2' class='span1 text-center no-visible no-search'>ID</th>
								<th rowspan='2' class='span1 text-center no-visible no-search'>ID</th>
							  	<th rowspan='2' class='text-center' style='width:150px;'>FLIGHT</th>
							  	<th colspan='5' class='text-center' style='width:250px;'>ACTUAL MEAL</th>
							  	<th colspan='5' class='text-center' style='width:250px;'>SPARE MEAL</th>
							  	<th rowspan='2' class='text-center' style='width:100px;'>FROZEN<br/>MEAL</th>
							  	<th rowspan='2' class='text-center' style='width:100px;'>REMARK</th>
						  	</tr>
						  	<tr>
						  		<th class='text-center' style='width:50px;'>F/C</th>
							  	<th class='text-center' style='width:50px;'>B/C</th>
							  	<th class='text-center' style='width:50px;'>Y/C</th>
							  	<th class='text-center' style='width:50px;'>C/R</th>
							  	<th class='text-center' style='width:50px;'>C/P</th>
							  	<th class='text-center' style='width:50px;'>F/C</th>
							  	<th class='text-center' style='width:50px;'>B/C</th>
							  	<th class='text-center' style='width:50px;'>Y/C</th>
							  	<th class='text-center' style='width:50px;'>C/R</th>
							  	<th class='text-center' style='width:50px;'>C/P</th>
						  	</tr>
						</thead>
						
						<tfoot>
							<tr>
						  		<th rowspan='2' class='span1 text-center'>NO</th>
								<th rowspan='2' class='span1'>ID</th>
								<th rowspan='2' class='span1'>ID</th>
							  	<th rowspan='2' class='text-center' style='width:150px;'>FLIGHT</th>
							  	<th class='text-center' style='width:50px;'>F/C</th>
							  	<th class='text-center' style='width:50px;'>B/C</th>
							  	<th class='text-center' style='width:50px;'>Y/C</th>
							  	<th class='text-center' style='width:50px;'>C/R</th>
							  	<th class='text-center' style='width:50px;'>C/P</th>
							  	<th class='text-center' style='width:50px;'>F/C</th>
							  	<th class='text-center' style='width:50px;'>B/C</th>
							  	<th class='text-center' style='width:50px;'>Y/C</th>
							  	<th class='text-center' style='width:50px;'>C/R</th>
							  	<th class='text-center' style='width:50px;'>C/P</th>
							  	<th rowspan='2' class='text-center' style='width:100px;'>FROZEN<br/>MEAL</th>
							  	<th rowspan='2' class='text-center' style='width:100px;'>REMARK</th>
						  	</tr>
						  	<tr>
							  	<th colspan='5' class='text-center' style='width:250px;'>ACTUAL MEAL</th>
							  	<th colspan='5' class='text-center' style='width:250px;'>SPARE MEAL</th>
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