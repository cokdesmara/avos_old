<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$id = $secure->sanitize($_GET["id"]);

$query = $mysqli->query("select t_aviation.id as id, t_aviation.code as code, t_aviation.name as name, t_aviation.active as active, t_user.name as user, t_aviation.modified as modified from t_aviation left join t_user on t_aviation.user = t_user.id where t_aviation.id = '".$id."'");
$row = $query->num_rows;
$r = $query->fetch_assoc();

if ($row > 0) {
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("AVIATION DATA", "index.php?page=aviation");
	$breadcrumb->append_crumb("DETAIL AVIATION", "#");
	echo $breadcrumb->breadcrumb();
	
	$active = $r["active"] == "Y" ? "YES" : "NO";
	$modified = $datetime->indonesian_datetime($r["modified"]);
	
	echo "<div class='panel'>
			<div class='panel-label'>AVIATION REFERENCE</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>DETAIL AVIATION</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$r["user"]."</div>
				</div>
				<div class='separator'></div>";
				
				if (!empty($_SESSION["aviation"])) {
				  	echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["aviation"]."</center></div>";
					unset($_SESSION["aviation"]);
				}
				
		  echo "<div class='form-horizontal'>
		  			<table class='field-table'>
					  	<tr>
							<th><label>CODE</label></th>
							<td style='width:20px;'>:</td>
							<td>".$r["code"]."</td>
						</tr>
						<tr>
							<th><label>NAME</label></th>
							<td>:</td>
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
		                    <td><button type='button' class='btn btn-success' onclick=\"window.location.href='index.php?page=aviation&act=edit&id=".$id."';\"><i class='icon-edit icon-white'></i> EDIT</button>&nbsp;&nbsp;&nbsp;
								<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=aviation';\"><i class='icon-arrow-left icon-white'></i> BACK</button></td>
		                </tr>
					</table>
				</div>
				
			</div>
	    </div>";
} else {
	include "errors/404.php";
}
?>