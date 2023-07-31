<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$id = $secure->sanitize($_GET["id"]);

$query = $mysqli->query("select a.id as id, a.name as name, a.email as email, a.privilege as privilege, a.active as active, b.name as user, a.modified as modified, a.login as login from t_user as a left join t_user as b on a.user = b.id where a.id = '".$id."'");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("USER DATA", "index.php?page=user");
	$breadcrumb->append_crumb("DETAIL USER", "#");
	echo $breadcrumb->breadcrumb();
	
	$email = strtolower($r["email"]);
	$active = $r["active"] == "Y" ? "YES" : "NO";
	$modified = $datetime->indonesian_datetime($r["modified"]);
	$login = strtoupper($datetime->time_ago($r["login"]));
	$time = !empty($r["login"]) ? $datetime->indonesian_datetime($r["login"]) : "-";
	
	echo "<div class='panel'>
			<div class='panel-label'>USER MANAGEMENT</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>DETAIL USER</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["user"])) {
				  	echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["user"]."</center></div>";
					unset($_SESSION["user"]);
				}
				
		  echo "<div class='form-horizontal'>
		  			<table class='field-table'>
					  	<tr>
							<th><label>NAME</label></th>
							<td style='width:20px;'>:</td>
							<td>".$r["name"]."</td>
						</tr>
						<tr>
							<th><label>E-MAIL</label></th>
							<td>:</td>
							<td><a href='mailto:".$email."'>".$r["email"]."</a></td>
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
		                    <th></th>
		                    <td></td>
		                    <td><button type='button' class='btn btn-success' onclick=\"window.location.href='index.php?page=user&act=edit&id=".$id."';\"><i class='icon-edit icon-white'></i> EDIT</button>&nbsp;&nbsp;&nbsp;
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=user';\"><i class='icon-arrow-left icon-white'></i> BACK</button></td>
		                </tr>
					</table>
				</div>
				
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>