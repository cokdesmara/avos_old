<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$id = $secure->sanitize($_GET["id"]);

$query = $mysqli->query("select t_config.id as id, t_config.register as register, t_aircraft.name as name_aircraft, t_equipment.name as name_equipment, t_config.seat_fc as seat_fc, t_config.seat_bc as seat_bc, t_config.seat_yc as seat_yc, t_config.seat_cr as seat_cr, t_config.seat_cp as seat_cp, t_config.active as active, t_user.name as user, t_config.modified as modified from t_config left join t_aircraft on t_config.aircraft = t_aircraft.id left join t_equipment on t_config.equipment = t_equipment.id left join t_user on t_config.user = t_user.id where t_config.id = '".$id."'");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("CONFIG DATA", "index.php?page=config");
	$breadcrumb->append_crumb("DETAIL CONFIG", "#");
	echo $breadcrumb->breadcrumb();
	
	$active = $r["active"] == "Y" ? "YES" : "NO";
	$modified = $datetime->indonesian_datetime($r["modified"]);
	
	echo "<div class='panel'>
			<div class='panel-label'>CONFIG REFERENCE</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>DETAIL CONFIG</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["config"])) {
				  	echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["config"]."</center></div>";
					unset($_SESSION["config"]);
				}
				
		  echo "<div class='form-horizontal'>
		  			<table class='field-table'>
					  	<tr>
							<th><label>REGISTER</label></th>
							<td style='width:20px;'>:</td>
							<td>".$r["register"]."</td>
						</tr>
						<tr>
							<th><label>AIRCRAFT</label></th>
							<td>:</td>
							<td>".$r["name_aircraft"]."</td>
						</tr>
						<tr>
							<th><label>EQUIPMENT</label></th>
							<td>:</td>
							<td>".$r["name_equipment"]."</td>
						</tr>
						<tr>
							<th><label>SEAT F/C</label></th>
							<td>:</td>
							<td>".number_format($r["seat_fc"], 0, ",", ".")."</td>
						</tr>
						<tr>
							<th><label>SEAT B/C</label></th>
							<td>:</td>
							<td>".number_format($r["seat_bc"], 0, ",", ".")."</td>
						</tr>
						<tr>
							<th><label>SEAT Y/C</label></th>
							<td>:</td>
							<td>".number_format($r["seat_yc"], 0, ",", ".")."</td>
						</tr>
						<tr>
							<th><label>SEAT C/R</label></th>
							<td>:</td>
							<td>".number_format($r["seat_cr"], 0, ",", ".")."</td>
						</tr>
						<tr>
							<th><label>SEAT C/P</label></th>
							<td>:</td>
							<td>".number_format($r["seat_cp"], 0, ",", ".")."</td>
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
		                    <td><button type='button' class='btn btn-success' onclick=\"window.location.href='index.php?page=config&act=edit&id=".$id."';\"><i class='icon-edit icon-white'></i> EDIT</button>&nbsp;&nbsp;&nbsp;
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=config';\"><i class='icon-arrow-left icon-white'></i> BACK</button></td>
		                </tr>
					</table>
				</div>
				
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>