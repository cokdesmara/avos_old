<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/mealorderpob/mealorderpob_action.php";
$date = $secure->sanitize($_GET["date"]);

$query = $mysqli->query("select t_header.date as date from t_header where t_header.date = '".$datetime->database_date($date)."' group by t_header.date");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("MEAL ORDER & P.O.B DATA", "index.php?page=mealorderpob");
	$breadcrumb->append_crumb("DETAIL MEAL ORDER & P.O.B", "#");
	echo $breadcrumb->breadcrumb();
	
	$query_user = $mysqli->query("select t_user.name as user, t_meal_order_pob.modified as modified from t_meal_order_pob left join t_header on t_meal_order_pob.header = t_header.id left join t_user on t_meal_order_pob.user = t_user.id where t_header.date = '".$datetime->database_date($date)."' order by t_meal_order_pob.modified desc limit 0, 1");
	$u = $query_user->fetch_assoc();
	
	$day = $datetime->get_day($r["date"]);
	$modified = !empty($u["modified"]) ? $datetime->indonesian_datetime($u["modified"]) : "-";
	$user = !empty($u["user"]) ? $u["user"] : "-";
	
	echo "<div class='panel'>
			<div class='panel-label'>MEAL ORDER & P.O.B ENTRY</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>DETAIL FINAL MEAL ORDER & P.O.B</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$user."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["mealorderpob"])) {
				  	echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["mealorderpob"]."</center></div>";
					unset($_SESSION["mealorderpob"]);
				}
				
		  echo "<div class='form-horizontal'>
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
            			<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=mealorderpob';\"><i class='icon-arrow-left icon-white'></i> BACK</button>";
						if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
                  			echo "&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success' onclick=\"window.location.href='index.php?page=mealorderpob&act=edit&date=".$date."';\"><i class='icon-edit icon-white'></i> EDIT</button>&nbsp;&nbsp;
                  				  <button type='button' class='btn btn-success' data-toggle='modal' data-target='#popup'><i class='icon-trash icon-white'></i> DELETE</button>";
						}
					echo "</div>
					
					<table id='dt-mealorderpob-detail' class='table table-bordered table-condensed table-striped table-hover'>
						<thead>
							<tr>
						  		<th rowspan='2' class='span1 text-center no-sort no-search'>NO</th>
								<th rowspan='2' class='span1 text-center no-visible no-search'>ID</th>
								<th rowspan='2' class='span1 text-center no-visible no-search'>ID</th>
							  	<th rowspan='2' class='text-center' style='width:100px;'>FLIGHT</th>
								<th rowspan='2' class='text-center' style='width:100px;'>CONFIG</th>
								<th colspan='5' class='text-center' style='width:250px;'>MEAL ORDER</th>
								<th colspan='5' class='text-center' style='width:250px;'>PAX ON BOARD</th>
								<th colspan='3' class='text-center' style='width:150px;'>SPML</th>
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
							  	<th class='text-center' style='width:50px;'>BBML</th>
							  	<th class='text-center' style='width:50px;'>KSML</th>
							  	<th class='text-center' style='width:50px;'>HCAKE</th>
							</tr>
						</thead>
						
						<tfoot>
							<tr>
						  		<th rowspan='2' class='span1 text-center'>NO</th>
								<th rowspan='2' class='span1'>ID</th>
								<th rowspan='2' class='span1'>ID</th>
							  	<th rowspan='2' class='text-center' style='width:100px;'>FLIGHT</th>
							  	<th rowspan='2' class='text-center' style='width:100px;'>CONFIG</th>
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
							  	<th class='text-center' style='width:50px;'>BBML</th>
							  	<th class='text-center' style='width:50px;'>KSML</th>
							  	<th class='text-center' style='width:50px;'>HCAKE</th>
							</tr>
							<tr>
								<th colspan='5' class='text-center' style='width:300px;'>MEAL ORDER</th>
								<th colspan='5' class='text-center' style='width:300px;'>PAX ON BOARD</th>
								<th colspan='3' class='text-center' style='width:150px;'>SPML</th>
							</tr>
						</tfoot>
				 	</table>
				</div>
				
			</div>
	    </div>
	    
	    <div id='popup' class='modal hide fade message' tabindex='-1' role='dialog' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
		  	<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4>ATTENTION</h4>
			</div>
			<div class='modal-body'>
				<p class='text-center'>ARE YOU SURE TO DELETE ALL THIS RECORDS ?</p>
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-success' onclick=\"window.location.href='".$action."?page=mealorderpob&act=clear&date=".$date."';\"><i class='icon-ok icon-white'></i> YES</button>&nbsp;
				<button type='button' class='btn btn-inverse' data-dismiss='modal' aria-hidden='true'><i class='icon-remove icon-white'></i> NO</button>
			</div>
	   	</div>";
} else {
	include "errors/404.php";
}
?>