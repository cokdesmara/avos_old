<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$query = $mysqli->query("select t_profile.id as id, t_profile.company as company, t_profile.branch as branch, t_profile.address as address, t_profile.phone as phone, t_profile.fax as fax, t_profile.email as email, t_profile.website as website, t_user.name as user, t_profile.modified as modified from t_profile left join t_user on t_profile.user = t_user.id");
$r = $query->fetch_assoc();

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("DETAIL PROFILE", "#");
echo $breadcrumb->breadcrumb();

$email = strtolower($r["email"]);
$website = "http://".strtolower($r["website"]);
$modified = $datetime->indonesian_datetime($r["modified"]);

echo "<div class='panel'>
		<div class='panel-label'>COMPANY PROFILE</div>
	  	<div class='panel-page'>
	  		
		  	<div class='panel-info'>
			  	<div class='title pull-left'>DETAIL PROFILE</div>
				<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
			</div>
			<div class='separator'></div>";
			
			if (!empty($_SESSION["profile"])) {
			  	echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["profile"]."</center></div>";
				unset($_SESSION["profile"]);
			}
			
	  echo "<div class='form-horizontal'>
		  		<table class='field-table'>
					<tr>
						<th><label>COMPANY</label></th>
						<td style='width:20px;'>:</td>
						<td>".$r["company"]."</td>
					</tr>
				  	<tr>
						<th><label>BRANCH</label></th>
						<td>:</td>
						<td>".$r["branch"]."</td>
					</tr>
					<tr>
						<th style='vertical-align:top;'><label>ADDRESS</label></th>
						<td style='vertical-align:top;'>:</td>
						<td><div style='width:40%;'>".$r["address"]."</div></td>
					</tr>
					<tr>
						<th><label>PHONE</label></th>
						<td>:</td>
						<td>".$r["phone"]."</td>
					</tr>
					<tr>
						<th><label>FAX</label></th>
						<td>:</td>
						<td>".$r["fax"]."</td>
					</tr>
					<tr>
						<th><label>E-MAIL</label></th>
						<td>:</td>
						<td><a href='mailto:".$email."'>".$r["email"]."</a></td>
					</tr>
					<tr>
						<th><label>WEBSITE</label></th>
						<td>:</td>
						<td><a href='".$website."' target='_blank'>".$r["website"]."</a></td>
					</tr>
					<tr>
	                    <td colspan='3'><div class='separator'></div></td>
	                </tr>";
					if ($_SESSION["user_privilege"] == "ADMINISTRATOR") {
					  echo "<tr>
			              		<th></th>
			                	<td></td>
			                	<td><button type='button' class='btn btn-success' onclick=\"window.location.href='index.php?page=profile&act=edit';\"><i class='icon-edit icon-white'></i> EDIT</button></td>
			              	</tr>";
					}
		  echo "</table>
	  		</div>
		
		</div>
    </div>";
?>