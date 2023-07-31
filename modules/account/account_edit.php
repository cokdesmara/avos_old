<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/account/account_action.php";

$query = $mysqli->query("select a.id as id, a.name as name, a.email as email, a.privilege as privilege, a.active as active, b.name as user, a.modified as modified, a.login as login from t_user as a left join t_user as b on a.user = b.id where a.id = '".$_SESSION["user_id"]."'");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("DETAIL ACCOUNT", "index.php?page=account");
	$breadcrumb->append_crumb("EDIT ACCOUNT", "#");
	echo $breadcrumb->breadcrumb();
	
	$active = $r["active"] == "Y" ? "YES" : "NO";
	$modified = $datetime->indonesian_datetime($r["modified"]);
	$login = strtoupper($datetime->time_ago($r["login"]));
	$time = !empty($r["login"]) ? $datetime->indonesian_datetime($r["login"]) : "-";
	
	echo "<div class='panel'>
			<div class='panel-label'>ACCOUNT MANAGEMENT</div>
		  	<div class='panel-page'>
		  		
				<div class='panel-info'>
				  	<div class='title pull-left'>EDIT ACCOUNT</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["account"])) {
			  		echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["account"]."</center></div>";
					unset($_SESSION["account"]);
				}
				
	      echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=account&act=update'>
				 	<input type='hidden' name='id' value='".$r["id"]."' />
				 	<input type='hidden' name='email' value='".$r["email"]."' />
					<table class='field-table'>
					  	<tr>
							<th><label for='name'>NAME <span class='red'>*</span></label></th>
							<td style='width:20px;'>:</td>
							<td><input type='text' id='name' name='name' class='input-xlarge input-upper validate(required, maxlength(100))' value='".$r["name"]."' autofocus /></td>
						</tr>
						<tr>
							<th><label for='email'>E-MAIL</label></th>
							<td>:</td>
							<td><input type='text' id='email' name='email' class='input-large input-upper validate(email, maxlength(50))' value='".$r["email"]."' autocomplete='off' disabled /></td>
						</tr>
						<tr>
							<th><label for='password'>PASSWORD</label></th>
							<td>:</td>
							<td><input type='password' id='password' name='password' class='input-medium input-upper validate(maxlength(50))' autocomplete='off' /></td>
						</tr>
						<tr>
							<th><label>PRIVILEGE</label></th>
							<td>:</td>
							<td>".$r["privilege"]."</td>
						</tr>
						<tr>
							<th><label>ACTIVE</label></th>
							<td>:</td>
							<td>".$active."</td>
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
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=account';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
	                    </tr>
					</table>
				</form>
				
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>