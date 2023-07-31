<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/flight/flight_action.php";
$id = $secure->sanitize($_GET["id"]);

$query = $mysqli->query("select t_flight.id as id, t_airline.id as id_airline, t_airline.code as code_airline, t_airline.name as name_airline, t_aviation.id as id_aviation, t_aviation.name as name_aviation, t_flight.flight_no as flight_no, t_flight.destination as destination, t_flight.active as active, t_user.name as user, t_flight.modified as modified from t_flight left join t_airline on t_flight.airline = t_airline.id left join t_aviation on t_flight.aviation = t_aviation.id left join t_user on t_flight.user = t_user.id where t_flight.id = '".$id."'");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("FLIGHT DATA", "index.php?page=flight");
	$breadcrumb->append_crumb("DETAIL FLIGHT", "index.php?page=flight&act=detail&id=".$id);
	$breadcrumb->append_crumb("EDIT FLIGHT", "#");
	echo $breadcrumb->breadcrumb();
	
	$destination = explode("-", $r["destination"]);
	$yes = $r["active"] == "Y" ? "checked" : "";
	$no = $r["active"] == "N" ? "checked" : "";
	$modified = $datetime->indonesian_datetime($r["modified"]);
	
	echo "<div class='panel'>
			<div class='panel-label'>FLIGHT REFERENCE</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>EDIT FLIGHT</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["flight"])) {
			  		echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["flight"]."</center></div>";
					unset($_SESSION["flight"]);
				}
				
		  echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=flight&act=update'>
				  	<input type='hidden' id='id' name='id' value='".$r["id"]."' />
				  	<input type='hidden' id='ori_airline' name='ori_airline' value='".$r["id_airline"]."' />
				  	<input type='hidden' id='ori_flight_no' name='ori_flight_no' value='".$r["flight_no"]."' />
					<table class='field-table'>
					  	<tr>
							<th><label for='airline'>AIRLINE <span class='red'>*</span></label></th>
							<td style='width:20px;'>:</td>
							<td><input type='text' id='airline' name='airline' class='input-xlarge input-upper validate(choose) airline' data-placeholder='-- CHOOSE AIRLINE --' init-id='".$r["id_airline"]."|".$r["code_airline"]."' init-text='".$r["name_airline"]." (".$r["code_airline"].")' value='".$r["id_airline"]."|".$r["code_airline"]."' autofocus /></td>
						</tr>
						<tr>
							<th><label for='aviation'>AVIATION <span class='red'>*</span></label></th>
							<td>:</td>
							<td><input type='text' id='aviation' name='aviation' class='input-large input-upper validate(choose) aviation' data-placeholder='-- CHOOSE AVIATION --' init-id='".$r["id_aviation"]."' init-text='".$r["name_aviation"]."' value='".$r["id_aviation"]."' /></td>
						</tr>
						<tr>
							<th><label for='flight_no'>FLIGHT NO. <span class='red'>*</span></label></th>
							<td>:</td>
							<td><input type='text' id='flight_no' name='flight_no' class='input-mini input-upper validate(required, maxlength(10))' value='".$r["flight_no"]."' /></td>
						</tr>
						<tr>
							<th><label for='from'>DESTINATION <span class='red'>*</span></label></th>
							<td>:</td>
							<td><input type='text' id='from' name='from' class='input-mini input-upper validate(required, maxlength(5))' value='".$destination[0]."' /> - <input type='text' id='to' name='to' class='input-mini input-upper validate(required, maxlength(5))' value='".$destination[1]."' /></td>
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
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=flight&act=detail&id=".$id."';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
	                    </tr>
					</table>
				</form>
			  
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>