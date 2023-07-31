<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/config/config_action.php";
$id = $secure->sanitize($_GET["id"]);

$query = $mysqli->query("select t_config.id as id, t_config.register as register, t_aircraft.id as id_aircraft, t_aircraft.name as name_aircraft, t_equipment.id as id_equipment, t_equipment.name as name_equipment, t_config.seat_fc as seat_fc, t_config.seat_bc as seat_bc, t_config.seat_yc as seat_yc, t_config.seat_cr as seat_cr, t_config.seat_cp as seat_cp, t_config.active as active, t_user.name as user, t_config.modified as modified from t_config left join t_aircraft on t_config.aircraft = t_aircraft.id left join t_equipment on t_config.equipment = t_equipment.id left join t_user on t_config.user = t_user.id where t_config.id = '".$id."'");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("CONFIG DATA", "index.php?page=config");
	$breadcrumb->append_crumb("DETAIL CONFIG", "index.php?page=config&act=detail&id=".$id);
	$breadcrumb->append_crumb("EDIT CONFIG", "#");
	echo $breadcrumb->breadcrumb();
	
	$yes = $r["active"] == "Y" ? "checked" : "";
	$no = $r["active"] == "N" ? "checked" : "";
	$modified = $datetime->indonesian_datetime($r["modified"]);
	
	echo "<div class='panel'>
			<div class='panel-label'>CONFIG REFERENCE</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>EDIT CONFIG</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["config"])) {
			  		echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["config"]."</center></div>";
					unset($_SESSION["config"]);
				}
				
		  echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=config&act=update'>
				  	<input type='hidden' id='id' name='id' value='".$r["id"]."' />
				  	<input type='hidden' id='ori_register' name='ori_register' value='".$r["register"]."' />
				  	<input type='hidden' id='ori_revision' name='ori_revision' value='".$r["revision"]."' />
					<table class='field-table'>
					  	<tr>
							<th><label for='register'>REGISTER <span class='red'>*</span></label></th>
							<td style='width:20px;'>:</td>
							<td><input type='text' id='register' name='register' class='input-small input-upper validate(required, maxlength(10))' value='".$r["register"]."' autofocus /></td>
						</tr>
					  	<tr>
							<th><label for='aircraft'>AIRCRAFT <span class='red'>*</span></label></th>
							<td>:</td>
							<td><input type='text' id='aircraft' name='aircraft' class='input-large input-upper validate(choose) aircraft' data-placeholder='-- CHOOSE AIRCRAFT --' init-id='".$r["id_aircraft"]."' init-text='".$r["name_aircraft"]."' value='".$r["id_aircraft"]."' /></td>
						</tr>
						<tr>
							<th><label for='equipment'>EQUIPMENT <span class='red'>*</span></label></th>
							<td>:</td>
							<td><input type='text' id='equipment' name='equipment' class='input-large input-upper validate(choose) equipment' data-placeholder='-- CHOOSE EQUIPMENT --' init-id='".$r["id_equipment"]."' init-text='".$r["name_equipment"]."' value='".$r["id_equipment"]."' /></td>
						</tr>
						<tr>
							<th><label for='seat_fc'>SEAT F/C <span class='red'>*</span></label></th>
							<td>:</td>
							<td><input type='text' id='seat_fc' name='seat_fc' class='input-mini input-upper decimal-mask validate(required)' value='".$r["seat_fc"]."' /></td>
						</tr>
						<tr>
							<th><label for='seat_bc'>SEAT B/C <span class='red'>*</span></label></th>
							<td>:</td>
							<td><input type='text' id='seat_bc' name='seat_bc' class='input-mini input-upper decimal-mask validate(required)' value='".$r["seat_bc"]."' /></td>
						</tr>
						<tr>
							<th><label for='seat_yc'>SEAT Y/C <span class='red'>*</span></label></th>
							<td>:</td>
							<td><input type='text' id='seat_yc' name='seat_yc' class='input-mini input-upper decimal-mask validate(required)' value='".$r["seat_yc"]."' /></td>
						</tr>
						<tr>
							<th><label for='seat_cr'>SEAT C/R <span class='red'>*</span></label></th>
							<td>:</td>
							<td><input type='text' id='seat_cr' name='seat_cr' class='input-mini input-upper decimal-mask validate(required)' value='".$r["seat_cr"]."' /></td>
						</tr>
						<tr>
							<th><label for='seat_cp'>SEAT C/P <span class='red'>*</span></label></th>
							<td>:</td>
							<td><input type='text' id='seat_cp' name='seat_cp' class='input-mini input-upper decimal-mask validate(required)' value='".$r["seat_cp"]."' /></td>
						</tr>
						<tr>
							<th><label for='active'>ACTIVE</label></th>
							<td>:</td>
							<td><label class='radio inline'><input type='radio' id='active' name='active' value='Y' ".$yes." /> YES</label>&nbsp;&nbsp;&nbsp;
								<label class='radio inline'><input type='radio' id='active' name='active' value='N' ".$no." /> NO</label></td>
						</tr>
						<tr>
	                        <td colspan='3'><div class='separator'></div></td>
	                    </tr>
						<tr>
	                        <th></th>
	                        <td></td>
	                        <td><button type='submit' class='btn btn-success'><i class='icon-hdd icon-white'></i> SAVE</button>&nbsp;&nbsp;&nbsp;
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=config&act=detail&id=".$id."';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
	                    </tr>
					</table>
				</form>
			  
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>