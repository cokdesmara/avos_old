<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("MEAL ORDER & P.O.B DATA", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>MEAL ORDER & P.O.B ENTRY</div>
		<div class='panel-page'>
		  
		  	<div class='panel-info'>
			  	<div class='title pull-left'>FINAL MEAL ORDER & P.O.B DATA</div>
			</div>
			<div class='separator'></div>";
			
			if (!empty($_SESSION["mealorderpob"])) {
			  	echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["mealorderpob"]."</center></div>";
				unset($_SESSION["mealorderpob"]);
			}
			
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
	  			echo "<button type='button' class='btn btn-success pull-left' data-toggle='modal' data-target='#modal-date'><i class='icon-plus icon-white'></i> NEW</button>";
			}
			
	  echo "<table id='dt-mealorderpob' class='table table-bordered table-condensed table-striped table-hover'>
		      	<thead>
				  	<tr>
				  		<th rowspan='2' class='span1 text-center no-sort no-search'>NO</th>
				  		<th rowspan='2' class='span1 text-center no-visible no-search'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>TOTAL<br/>FLIGHT</th>
				  		<th colspan='5' class='text-center' style='width:250px;'>TOTAL MEAL ORDER</th>
				  		<th colspan='5' class='text-center' style='width:250px;'>TOTAL PAX ON BOARD</th>
				  		<th colspan='3' class='text-center' style='width:150px;'>TOTAL SPML</th>
				  		<th colspan='5' class='text-center' style='width:250px;'>TOTAL CONFIG</th>
					  	<th rowspan='2' class='text-center no-sort no-search' style='width:75px;'>ACTION</th>
				  	</tr>
				  	<tr>
				  		<th class='text-center' style='width:50px;'>F/C</th>
					  	<th class='text-center' style='width:50px;'>B/C</th>
					  	<th class='text-center' style='width:50px;'>Y/C</th>
					  	<th class='text-center' style='width:50px;'>C/R</th>
					  	<th class='text-center' style='width:50px;'>C/P</th>
					  	<th class='text-center' style='width:50px;'>F/C</th>
					  	<th class='text-center' style='width:50px;'>B/C</th>
					  	<th class='text-center' style='width:50px;'>Y/C</th>
					  	<th class='text-center' style='width:50px;'>C/R</th>
					  	<th class='text-center' style='width:50px;'>C/P</th>
					  	<th class='text-center' style='width:50px;'>BBML</th>
					  	<th class='text-center' style='width:50px;'>KSML</th>
					  	<th class='text-center' style='width:50px;'>HCAKE</th>
					  	<th class='text-center' style='width:50px;'>F/C</th>
					  	<th class='text-center' style='width:50px;'>B/C</th>
					  	<th class='text-center' style='width:50px;'>Y/C</th>
					  	<th class='text-center' style='width:50px;'>C/R</th>
					  	<th class='text-center' style='width:50px;'>C/P</th>
				  	</tr>
				</thead>
				
				<tfoot>
				  	<tr>
				  		<th rowspan='2' class='span1 text-center'>NO</th>
				  		<th rowspan='2' class='span1 text-center'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>TOTAL<br/>FLIGHT</th>
				  		<th class='text-center' style='width:50px;'>F/C</th>
					  	<th class='text-center' style='width:50px;'>B/C</th>
					  	<th class='text-center' style='width:50px;'>Y/C</th>
					  	<th class='text-center' style='width:50px;'>C/R</th>
					  	<th class='text-center' style='width:50px;'>C/P</th>
					  	<th class='text-center' style='width:50px;'>F/C</th>
					  	<th class='text-center' style='width:50px;'>B/C</th>
					  	<th class='text-center' style='width:50px;'>Y/C</th>
					  	<th class='text-center' style='width:50px;'>C/R</th>
					  	<th class='text-center' style='width:50px;'>C/P</th>
					  	<th class='text-center' style='width:50px;'>BBML</th>
					  	<th class='text-center' style='width:50px;'>KSML</th>
					  	<th class='text-center' style='width:50px;'>HCAKE</th>
					  	<th class='text-center' style='width:50px;'>F/C</th>
					  	<th class='text-center' style='width:50px;'>B/C</th>
					  	<th class='text-center' style='width:50px;'>Y/C</th>
					  	<th class='text-center' style='width:50px;'>C/R</th>
					  	<th class='text-center' style='width:50px;'>C/P</th>
					  	<th rowspan='2' class='text-center' style='width:75px;'>ACTION</th>
				  	</tr>
				  	<tr>
				  		<th colspan='5' class='text-center' style='width:250px;'>TOTAL MEAL ORDER</th>
				  		<th colspan='5' class='text-center' style='width:250px;'>TOTAL PAX ON BOARD</th>
				  		<th colspan='3' class='text-center' style='width:150px;'>TOTAL SPML</th>
				  		<th colspan='5' class='text-center' style='width:250px;'>TOTAL CONFIG</th>
				  	</tr>
				</tfoot>
		 	</table>
			
		</div>
	</div>
	
	<div id='modal-date' class='modal hide fade message' tabindex='-1' role='dialog' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
	  	<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
			<h4>FINAL MEAL ORDER & P.O.B</h4>
		</div>
		<form method='GET' action='".$_SERVER["PHP_SELF"]."'>
			<div class='modal-body'>
				<div class='form-horizontal' style='margin-bottom:10px;'>
					<input type='hidden' name='page' value='mealorderpob'>
					<input type='hidden' name='act' value='new'>
					<table class='field-table'>
						<tr>
							<th><label>CHOOSE DATE</label></th>
							<td style='width:20px;'>:</td>
							<td><div id='dp1' class='input-append date datepicker'><input type='text' id='date' name='date' class='input-small date-mask' required autofocus /><span class='add-on'><i class='icon-calendar'></i></span></div></td>
						</tr>
					</table>
				</div>
			</div>
			<div class='modal-footer'>
				<div class='pull-right'>
					<button type='submit' class='btn btn-success'><i class='icon-ok icon-white'></i> PROCESS</button>&nbsp;&nbsp;&nbsp;
					<button type='button' class='btn btn-inverse' data-dismiss='modal' aria-hidden='true'><i class='icon-remove icon-white'></i> CANCEL</button>
				</div>
			</div>
		</form>
   	</div>";
?>