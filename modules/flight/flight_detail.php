<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$id = $secure->sanitize($_GET["id"]);

$query = $mysqli->query("select t_flight.id as id, t_airline.code as code_airline, t_airline.name as name_airline, t_aviation.name as name_aviation, t_flight.flight_no as flight_no, t_flight.destination as destination, t_flight.active as active, t_user.name as user, t_flight.modified as modified from t_flight left join t_airline on t_flight.airline = t_airline.id left join t_aviation on t_flight.aviation = t_aviation.id left join t_user on t_flight.user = t_user.id where t_flight.id = '".$id."'");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("FLIGHT DATA", "index.php?page=flight");
	$breadcrumb->append_crumb("DETAIL FLIGHT", "#");
	echo $breadcrumb->breadcrumb();
	
	$active = $r["active"] == "Y" ? "YES" : "NO";
	$modified = $datetime->indonesian_datetime($r["modified"]);
	
	echo "<div class='panel'>
			<div class='panel-label'>FLIGHT REFERENCE</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>DETAIL FLIGHT</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["flight"])) {
				  	echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["flight"]."</center></div>";
					unset($_SESSION["flight"]);
				}
				
		  echo "<div class='form-horizontal'>
		  			<table class='field-table'>
					  	<tr>
							<th><label>AIRLINE</label></th>
							<td style='width:20px;'>:</td>
							<td>".$r["name_airline"]."</td>
						</tr>
						<tr>
							<th><label>AVIATION</label></th>
							<td>:</td>
							<td>".$r["name_aviation"]."</td>
						</tr>
						<tr>
							<th><label>FLIGHT NO.</label></th>
							<td>:</td>
							<td>".$r["code_airline"]."-".$r["flight_no"]."</td>
						</tr>
						<tr>
							<th><label>DESTINATION</label></th>
							<td>:</td>
							<td>".$r["destination"]."</td>
						</tr>
						<tr>
							<th><label>ACTIVE</label></th>
							<td>:</td>
							<td>".$active."</td>
						</tr>
						<tr>
		                    <td colspan='3'><div class='separator'></div></td>
		                </tr>
						<tr>
		                    <th></th>
		                    <td></td>
		                    <td><button type='button' class='btn btn-success' onclick=\"window.location.href='index.php?page=flight&act=edit&id=".$id."';\"><i class='icon-edit icon-white'></i> EDIT</button>&nbsp;&nbsp;&nbsp;
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=flight';\"><i class='icon-arrow-left icon-white'></i> BACK</button></td>
		                </tr>
					</table>
				</div>
				
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>