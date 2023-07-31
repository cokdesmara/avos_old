<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/status/status_action.php";
$id = $secure->sanitize($_GET["id"]);

$query = $mysqli->query("select t_status.id as id, t_status.code as code, t_status.name as name, t_status.active as active, t_user.name as user, t_status.modified as modified from t_status left join t_user on t_status.user = t_user.id where t_status.id = '".$id."'");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("STATUS DATA", "index.php?page=status");
	$breadcrumb->append_crumb("DETAIL STATUS", "index.php?page=status&act=detail&id=".$id);
	$breadcrumb->append_crumb("EDIT STATUS", "#");
	echo $breadcrumb->breadcrumb();
	
	$yes = $r["active"] == "Y" ? "checked" : "";
	$no = $r["active"] == "N" ? "checked" : "";
	$modified = $datetime->indonesian_datetime($r["modified"]);
	
	echo "<div class='panel'>
			<div class='panel-label'>STATUS REFERENCE</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>EDIT STATUS</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["status"])) {
			  		echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["status"]."</center></div>";
					unset($_SESSION["status"]);
				}
				
		  echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=status&act=update'>
				  	<input type='hidden' id='id' name='id' value='".$r["id"]."' />
				  	<input type='hidden' id='ori_code' name='ori_code' value='".$r["code"]."' />
				  	<input type='hidden' id='ori_name' name='ori_name' value='".$r["name"]."' />
					<table class='field-table'>
					  	<tr>
							<th><label for='code'>CODE <span class='red'>*</span></label></th>
							<td style='width:20px;'>:</td>
							<td><input type='text' id='code' name='code' class='input-mini input-upper validate(required, maxlength(10))' value='".$r["code"]."' autofocus /></td>
						</tr>
						<tr>
							<th><label for='name'>NAME <span class='red'>*</span></label></th>
							<td>:</td>
							<td><input type='text' id='name' name='name' class='input-medium input-upper validate(required, maxlength(50))' value='".$r["name"]."' /></td>
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
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=status&act=detail&id=".$id."';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
	                    </tr>
					</table>
				</form>
			  
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>