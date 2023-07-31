<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/equipment/equipment_action.php";
$id = $secure->sanitize($_GET["id"]);

$query = $mysqli->query("select t_equipment.id as id, t_equipment.name as name, t_equipment.active as active, t_user.name as user, t_equipment.modified as modified from t_equipment left join t_user on t_equipment.user = t_user.id where t_equipment.id = '".$id."'");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("EQUIPMENT DATA", "index.php?page=equipment");
	$breadcrumb->append_crumb("DETAIL EQUIPMENT", "index.php?page=equipment&act=detail&id=".$id);
	$breadcrumb->append_crumb("EDIT EQUIPMENT", "#");
	echo $breadcrumb->breadcrumb();
	
	$yes = $r["active"] == "Y" ? "checked" : "";
	$no = $r["active"] == "N" ? "checked" : "";
	$modified = $datetime->indonesian_datetime($r["modified"]);
	
	echo "<div class='panel'>
			<div class='panel-label'>EQUIPMENT REFERENCE</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>EDIT EQUIPMENT</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["equipment"])) {
			  		echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["equipment"]."</center></div>";
					unset($_SESSION["equipment"]);
				}
				
		  echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=equipment&act=update'>
				  	<input type='hidden' id='id' name='id' value='".$r["id"]."' />
				  	<input type='hidden' id='ori_name' name='ori_name' value='".$r["name"]."' />
					<table class='field-table'>
						<tr>
							<th><label for='name'>NAME <span class='red'>*</span></label></th>
							<td style='width:20px;'>:</td>
							<td><input type='text' id='name' name='name' class='input-xlarge input-upper validate(required, maxlength(50))' value='".$r["name"]."' autofocus /></td>
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
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=equipment&act=detail&id=".$id."';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
	                    </tr>
					</table>
				</form>
			  
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>