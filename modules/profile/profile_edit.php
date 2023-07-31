<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

if ($_SESSION["user_privilege"] == "ADMINISTRATOR") {
	$action = "modules/profile/profile_action.php";
	
	$query = $mysqli->query("select t_profile.id as id, t_profile.company as company, t_profile.branch as branch, t_profile.address as address, t_profile.phone as phone, t_profile.fax as fax, t_profile.email as email, t_profile.website as website, t_user.name as user, t_profile.modified as modified from t_profile left join t_user on t_profile.user = t_user.id");
    $r = $query->fetch_assoc();
    
    $breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("DETAIL PROFILE", "index.php?page=profile");
	$breadcrumb->append_crumb("EDIT PROFILE", "#");
	echo $breadcrumb->breadcrumb();
	
	$modified = $datetime->indonesian_datetime($r["modified"]);
	
	echo "<div class='panel'>
			<div class='panel-label'>COMPANY PROFILE</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>EDIT PROFILE</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["profile"])) {
			  		echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["profile"]."</center></div>";
					unset($_SESSION["profile"]);
				}
				
		  echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=profile&act=update'>
				  	<input type='hidden' name='id' value='".$r["id"]."' />
					<table class='field-table'>
					  	<tr>
							<th><label for='company'>COMPANY</label></th>
							<td style='width:20px;'>:</td>
							<td><input type='text' id='company' name='company' class='input-xlarge input-upper validate(required, maxlength(100))' value='".$r["company"]."' /></td>
						</tr>
						<tr>
							<th><label for='branch'>BRANCH</label></th>
							<td>:</td>
							<td><input type='text' id='branch' name='branch' class='input-xlarge input-upper validate(required, maxlength(100))' value='".$r["branch"]."' /></td>
						</tr>
						<tr>
							<th style='vertical-align:top;padding-top:5px;'><label for='address'>ADDRESS</label></th>
							<td style='vertical-align:top;padding-top:5px;'>:</td>
							<td><textarea id='address' name='address' class='input-xlarge input-upper validate(required, maxlength(255))' rows='3'>".$r["address"]."</textarea></td>
						</tr>
						<tr>
							<th><label for='phone'>PHONE</label></th>
							<td>:</td>
							<td><input type='text' id='phone' name='phone' class='input-large input-upper validate(maxlength(100))' value='".$r["phone"]."' /></td>
						</tr>
						<tr>
							<th><label for='fax'>FAX</label></th>
							<td>:</td>
							<td><input type='text' id='fax' name='fax' class='input-large input-upper validate(maxlength(100))' value='".$r["fax"]."' /></td>
						</tr>
						<tr>
							<th><label for='email'>E-MAIL</label></th>
							<td>:</td>
							<td><input type='text' id='email' name='email' class='input-large input-upper validate(maxlength(100))' value='".$r["email"]."' /></td>
						</tr>
						<tr>
							<th><label for='website'>WEBSITE</label></th>
							<td>:</td>
							<td><input type='text' id='website' name='website' class='input-large input-upper validate(maxlength(100))' value='".$r["website"]."' /></td>
						</tr>
						<tr>
                            <td colspan='3'><div class='separator'></div></td>
                        </tr>
						<tr>
                            <th></th>
                            <td></td>
                            <td><button type='submit' class='btn btn-success'><i class='icon-hdd icon-white'></i> SAVE</button>&nbsp;&nbsp;&nbsp;
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=profile';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
                        </tr>
					</table>
				</form>
			  
			</div>
        </div>";
} else {
	include "errors/401.php";
}
?>