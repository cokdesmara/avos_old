<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/flight/flight_action.php";

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("FLIGHT DATA", "index.php?page=flight");
$breadcrumb->append_crumb("NEW FLIGHT", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>FLIGHT REFERENCE</div>
	  	<div class='panel-page'>
	  		
		  	<div class='panel-info'>
			  	<div class='title pull-left'>NEW FLIGHT</div>
				<div class='modify pull-right'>LAST MODIFIED -, BY -</div>
			</div>
			<div class='separator'></div>";
			
			if (!empty($_SESSION["flight"])) {
			  	echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["flight"]."</center></div>";
				unset($_SESSION["flight"]);
			}
			
	  echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=flight&act=insert'>
				<table class='field-table'>
				  	<tr>
						<th><label for='airline'>AIRLINE <span class='red'>*</span></label></th>
						<td style='width:20px;'>:</td>
						<td><input type='text' id='airline' name='airline' class='input-xlarge input-upper validate(choose) airline' data-placeholder='-- CHOOSE AIRLINE --' autofocus /></td>
					</tr>
					<tr>
						<th><label for='aviation'>AVIATION <span class='red'>*</span></label></th>
						<td>:</td>
						<td><input type='text' id='aviation' name='aviation' class='input-large input-upper validate(choose) aviation' data-placeholder='-- CHOOSE AVIATION --' /></td>
					</tr>
					<tr>
						<th><label for='flight_no'>FLIGHT NO. <span class='red'>*</span></label></th>
						<td>:</td>
						<td><input type='text' id='flight_no' name='flight_no' class='input-mini input-upper validate(required, maxlength(10))' /></td>
					</tr>
					<tr>
						<th><label for='from'>DESTINATION <span class='red'>*</span></label></th>
						<td>:</td>
						<td><input type='text' id='from' name='from' class='input-mini input-upper validate(required, maxlength(5))' /> - <input type='text' id='to' name='to' class='input-mini input-upper validate(required, maxlength(5))' /></td>
					</tr>
					<tr>
						<th><label for='active'>ACTIVE</label></th>
						<td>:</td>
						<td><label class='radio inline'><input type='radio' id='active' name='active' value='Y' checked /> YES</label>&nbsp;&nbsp;&nbsp;
							<label class='radio inline'><input type='radio' id='active' name='active' value='N' /> NO</label></td>
					</tr>
					<tr>
                        <td colspan='3'><div class='separator'></div></td>
                    </tr>
					<tr>
                        <th></th>
                        <td></td>
                        <td><button type='submit' class='btn btn-success'><i class='icon-hdd icon-white'></i> SAVE</button>&nbsp;&nbsp;&nbsp;
							<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=flight';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
                    </tr>
				</table>
			</form>
		  
		</div>
    </div>";
?>