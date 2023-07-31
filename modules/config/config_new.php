<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/config/config_action.php";

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("CONFIG DATA", "index.php?page=config");
$breadcrumb->append_crumb("NEW CONFIG", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>CONFIG REFERENCE</div>
	  	<div class='panel-page'>
	  		
		  	<div class='panel-info'>
			  	<div class='title pull-left'>NEW CONFIG</div>
				<div class='modify pull-right'>LAST MODIFIED -, BY -</div>
			</div>
			<div class='separator'></div>";
			
			if (!empty($_SESSION["config"])) {
			  	echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["config"]."</center></div>";
				unset($_SESSION["config"]);
			}
			
	  echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=config&act=insert'>
				<table class='field-table'>
					<tr>
						<th><label for='register'>REGISTER <span class='red'>*</span></label></th>
						<td style='width:20px;'>:</td>
						<td><input type='text' id='register' name='register' class='input-small input-upper validate(required, maxlength(10))' autofocus /></td>
					</tr>
				  	<tr>
						<th><label for='aircraft'>AIRCRAFT <span class='red'>*</span></label></th>
						<td>:</td>
						<td><input type='text' id='aircraft' name='aircraft' class='input-large input-upper validate(choose) aircraft' data-placeholder='-- CHOOSE AIRCRAFT --' /></td>
					</tr>
					<tr>
						<th><label for='equipment'>EQUIPMENT <span class='red'>*</span></label></th>
						<td>:</td>
						<td><input type='text' id='equipment' name='equipment' class='input-large input-upper validate(choose) equipment' data-placeholder='-- CHOOSE EQUIPMENT --' /></td>
					</tr>
					<tr>
						<th><label for='seat_fc'>SEAT F/C <span class='red'>*</span></label></th>
						<td>:</td>
						<td><input type='text' id='seat_fc' name='seat_fc' class='input-mini input-upper decimal-mask validate(required)' /></td>
					</tr>
					<tr>
						<th><label for='seat_bc'>SEAT B/C <span class='red'>*</span></label></th>
						<td>:</td>
						<td><input type='text' id='seat_bc' name='seat_bc' class='input-mini input-upper decimal-mask validate(required)' /></td>
					</tr>
					<tr>
						<th><label for='seat_yc'>SEAT Y/C <span class='red'>*</span></label></th>
						<td>:</td>
						<td><input type='text' id='seat_yc' name='seat_yc' class='input-mini input-upper decimal-mask validate(required)' /></td>
					</tr>
					<tr>
						<th><label for='seat_cr'>SEAT C/R <span class='red'>*</span></label></th>
						<td>:</td>
						<td><input type='text' id='seat_cr' name='seat_cr' class='input-mini input-upper decimal-mask validate(required)' /></td>
					</tr>
					<tr>
						<th><label for='seat_cp'>SEAT C/P <span class='red'>*</span></label></th>
						<td>:</td>
						<td><input type='text' id='seat_cp' name='seat_cp' class='input-mini input-upper decimal-mask validate(required)' /></td>
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
							<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=config';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
                    </tr>
				</table>
			</form>
		  
		</div>
    </div>";
?>