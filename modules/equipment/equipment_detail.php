<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$id = $secure->sanitize($_GET["id"]);

$query = $mysqli->query("select t_equipment.id as id, t_equipment.name as name, t_equipment.active as active, t_user.name as user, t_equipment.modified as modified from t_equipment left join t_user on t_equipment.user = t_user.id where t_equipment.id = '".$id."'");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("EQUIPMENT DATA", "index.php?page=equipment");
	$breadcrumb->append_crumb("DETAIL EQUIPMENT", "#");
	echo $breadcrumb->breadcrumb();
	
	$active = $r["active"] == "Y" ? "YES" : "NO";
	$modified = $datetime->indonesian_datetime($r["modified"]);
	
	echo "<div class='panel'>
			<div class='panel-label'>EQUIPMENT REFERENCE</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>DETAIL EQUIPMENT</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["equipment"])) {
				  	echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["equipment"]."</center></div>";
					unset($_SESSION["equipment"]);
				}
				
		  echo "<div class='form-horizontal'>
		  			<table class='field-table'>
						<tr>
							<th><label>NAME</label></th>
							<td style='width:20px;'>:</td>
							<td>".$r["name"]."</td>
						</tr>
						<tr>
							<th><label>ACTIVE</label></th>
							<td>:</td>
							<td>".$active."</td>
						</tr>
						<tr>
		                    <td colspan='3'><div class='separator'></div></td>
		                </tr>
						<tr>
		                    <th></th>
		                    <td></td>
		                    <td><button type='button' class='btn btn-success' onclick=\"window.location.href='index.php?page=equipment&act=edit&id=".$id."';\"><i class='icon-edit icon-white'></i> EDIT</button>&nbsp;&nbsp;&nbsp;
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=equipment';\"><i class='icon-arrow-left icon-white'></i> BACK</button></td>
		                </tr>
					</table>
				</div>
				
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>