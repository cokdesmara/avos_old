<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/aircraft/aircraft_action.php";

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("AIRCRAFT DATA", "index.php?page=aircraft");
$breadcrumb->append_crumb("NEW AIRCRAFT", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>AIRCRAFT REFERENCE</div>
	  	<div class='panel-page'>
	  		
		  	<div class='panel-info'>
			  	<div class='title pull-left'>NEW AIRCRAFT</div>
				<div class='modify pull-right'>LAST MODIFIED -, BY -</div>
			</div>
			<div class='separator'></div>";
			
			if (!empty($_SESSION["aircraft"])) {
			  	echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["aircraft"]."</center></div>";
				unset($_SESSION["aircraft"]);
			}
			
	  echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=aircraft&act=insert'>
				<table class='field-table'>
				  	<tr>
						<th><label for='name'>NAME <span class='red'>*</span></label></th>
						<td style='width:20px;'>:</td>
						<td><input type='text' id='name' name='name' class='input-xlarge input-upper validate(required, maxlength(50))' autofocus /></td>
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
							<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=aircraft';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
                    </tr>
				</table>
			</form>
		  
		</div>
    </div>";
?>