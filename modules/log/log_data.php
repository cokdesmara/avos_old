<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/log/log_action.php";

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("LOG DATA", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>USER LOG</div>
		<div class='panel-page'>
		  	
		  	<div class='panel-info'>
			  	<div class='title pull-left'>LOG DATA</div>
			</div>
			<div class='separator'></div>";
			
			if (!empty($_SESSION["log"])) {
			  	echo "<div class='alert ".$_SESSION["alert"]."'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["log"]."</center></div>";
				unset($_SESSION["log"]);
				unset($_SESSION["alert"]);
			}
			
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR") {
	  			echo "<button type='button' class='btn btn-success pull-left' data-toggle='modal' data-target='#popup'><i class='icon-trash icon-white'></i> CLEAR</button>";
			}
			
	  echo "<table id='dt_log' class='table table-bordered table-condensed table-striped table-hover'>
		      	<thead>
				  	<tr>
					  	<th class='span1 text-center no-sort no-search'>NO</th>
					  	<th class='span1 text-center no-visible no-search'>ID</th>
					  	<th style='width:250px;'>NAME</th>
					  	<th>E-MAIL</th>
					  	<th style='width:100px;'>PRIVILEGE</th>
					  	<th class='text-center' style='width:65px;'>ACTIVE</th>
						<th style='width:130px;'>LAST LOGIN</th>
						<th style='width:120px;'>LOGIN TIME</th>
				  	</tr>
				</thead>
				
				<tfoot>
				  	<tr>
				  		<th class='span1 text-center'>NO</th>
					  	<th class='span1 text-center'>ID</th>
					  	<th style='width:250px;'>NAME</th>
					  	<th>E-MAIL</th>
					  	<th style='width:100px;'>PRIVILEGE</th>
					  	<th class='text-center' style='width:65px;'>ACTIVE</th>
						<th style='width:130px;'>LAST LOGIN</th>
						<th style='width:120px;'>LOGIN TIME</th>
				  	</tr>
				</tfoot>
		 	</table>
			
		</div>
	</div>
	
	<div id='popup' class='modal hide fade message' tabindex='-1' role='dialog' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
	  	<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
			<h4>ATTENTION</h4>
		</div>
		<div class='modal-body'>
			<p class='text-center'>ARE YOU SURE TO CLEAR ALL LOG DATA ?</p>
		</div>
		<div class='modal-footer'>
			<button type='button' class='btn btn-success' onclick=\"window.location.href='".$action."?page=log&act=clear';\"><i class='icon-ok icon-white'></i> YES</button>&nbsp;
			<button type='button' class='btn btn-inverse' data-dismiss='modal' aria-hidden='true'><i class='icon-remove icon-white'></i> NO</button>
		</div>
   	</div>";
?>