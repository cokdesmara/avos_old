<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/user/user_action.php";
$list_privilege = array(array("ADMINISTRATOR", "ADMINISTRATOR"), array("ORDER CENTER", "ORDER CENTER"), array("PRODUCTION", "PRODUCTION"), array("OPERATION GA", "OPERATION GA"), array("OPERATION NONGA", "OPERATION NONGA"), array("MONITORING", "MONITORING"));
$id = $secure->sanitize($_GET["id"]);

$query = $mysqli->query("select a.id as id, a.name as name, a.email as email, a.privilege as privilege, a.active as active, b.name as user, a.modified as modified, a.login as login from t_user as a left join t_user as b on a.user = b.id where a.id = '".$id."'");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("USER DATA", "index.php?page=user");
	$breadcrumb->append_crumb("DETAIL USER", "index.php?page=user&act=detail&id=".$id);
	$breadcrumb->append_crumb("EDIT USER", "#");
	echo $breadcrumb->breadcrumb();
	
	$yes = $r["active"] == "Y" ? "checked" : "";
	$no = $r["active"] == "N" ? "checked" : "";
	$modified = $datetime->indonesian_datetime($r["modified"]);
	$login = strtoupper($datetime->time_ago($r["login"]));
	$time = !empty($r["login"]) ? $datetime->indonesian_datetime($r["login"]) : "-";

	echo "<div class='panel'>
			<div class='panel-label'>USER MANAGEMENT</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>EDIT USER</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["user"])) {
			  		echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["user"]."</center></div>";
					unset($_SESSION["user"]);
				}
				
		  echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=user&act=update'>
				  	<input type='hidden' id='id' name='id' value='".$r["id"]."' />
				  	<input type='hidden' id='ori_email' name='ori_email' value='".$r["email"]."' />
					<table class='field-table'>
					  	<tr>
							<th><label for='name'>NAME <span class='red'>*</span></label></th>
							<td style='width:20px;'>:</td>
							<td><input type='text' id='name' name='name' class='input-xlarge input-upper validate(required, maxlength(100))' value='".$r["name"]."' autofocus /></td>
						</tr>
						<tr>
							<th><label for='email'>E-MAIL <span class='red'>*</span></label></th>
							<td>:</td>
							<td><input type='text' id='email' name='email' class='input-large input-upper validate(required, email, maxlength(50))' value='".$r["email"]."' autocomplete='off' /></td>
						</tr>
						<tr>
							<th><label for='password'>PASSWORD</label></th>
							<td>:</td>
							<td><input type='password' id='password' name='password' class='input-medium input-upper validate(maxlength(50))' autocomplete='off' /></td>
						</tr>
						<tr>
							<th><label for='privilege'>PRIVILEGE <span class='red'>*</span></label></th>
							<td>:</td>
							<td><select id='privilege' name='privilege' class='select2-single input-large validate(required)' data-placeholder='-- CHOOSE PRIVILEGE --'>
								<option value='' selected></option>";
								foreach ($list_privilege as $value) {
									if ($r["privilege"] == $value["0"]) {
									  	echo "<option value='".$value["0"]."' selected>".$value["1"]."</option>";
								  	} else {
									  	echo "<option value='".$value["0"]."'>".$value["1"]."</option>";
								  	}
								}
					      	echo "</select></td>
						</tr>
						<tr>
							<th><label for='active'>ACTIVE</label></th>
							<td>:</td>
							<td><label class='radio inline'><input type='radio' id='active' name='active' value='Y' ".$yes." /> YES</label>&nbsp;&nbsp;&nbsp;
								<label class='radio inline'><input type='radio' id='active' name='active' value='N' ".$no." /> NO</label></td>
						</tr>
						<tr>
							<th><label>LAST LOGIN</label></th>
							<td>:</td>
							<td>".$login."</td>
						</tr>
						<tr>
							<th><label>LOGIN TIME</label></th>
							<td>:</td>
							<td>".$time."</td>
						</tr>
						<tr>
	                        <td colspan='3'><div class='separator'></div></td>
	                    </tr>
						<tr>
							<th style='vertical-align:top;'><label class='muted'>NOTE</label></th>
	                        <td style='vertical-align:top;'><div class='muted'>:<div></td>
							<td><div class='muted'><em>LEAVE PASSWORD BLANK IF YOU DON'T WANT TO CHANGE PASSWORD !</em></div></td>
						</tr>
						<tr>
	                        <td colspan='3'><div class='separator'></div></td>
	                    </tr>
						<tr>
	                        <th></th>
	                        <td></td>
	                        <td><button type='submit' class='btn btn-success'><i class='icon-hdd icon-white'></i> SAVE</button>&nbsp;&nbsp;&nbsp;
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=user&act=detail&id=".$id."';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
	                    </tr>
					</table>
				</form>
			  
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>