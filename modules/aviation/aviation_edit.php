<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/aviation/aviation_action.php";
$id = $secure->sanitize($_GET["id"]);

$query = $mysqli->query("select t_aviation.id as id, t_aviation.code as code, t_aviation.name as name, t_aviation.active as active, t_user.name as user, t_aviation.modified as modified from t_aviation left join t_user on t_aviation.user = t_user.id where t_aviation.id = '".$id."'");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("AVIATION DATA", "index.php?page=aviation");
	$breadcrumb->append_crumb("DETAIL AVIATION", "index.php?page=aviation&act=detail&id=".$id);
	$breadcrumb->append_crumb("EDIT AVIATION", "#");
	echo $breadcrumb->breadcrumb();
	
	$yes = $r["active"] == "Y" ? "checked" : "";
	$no = $r["active"] == "N" ? "checked" : "";
	$modified = $datetime->indonesian_datetime($r["modified"]);
	
	echo "<div class='panel'>
			<div class='panel-label'>AVIATION REFERENCE</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>EDIT AVIATION</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["aviation"])) {
			  		echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["aviation"]."</center></div>";
					unset($_SESSION["aviation"]);
				}
				
		  echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=aviation&act=update'>
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
							<td><input type='text' id='name' name='name' class='input-xlarge input-upper validate(required, maxlength(50))' value='".$r["name"]."' /></td>
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
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=aviation&act=detail&id=".$id."';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
	                    </tr>
					</table>
				</form>
			  
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>