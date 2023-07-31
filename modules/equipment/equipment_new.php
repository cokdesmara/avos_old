<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/equipment/equipment_action.php";

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("EQUIPMENT DATA", "index.php?page=equipment");
$breadcrumb->append_crumb("NEW EQUIPMENT", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>EQUIPMENT REFERENCE</div>
	  	<div class='panel-page'>
	  		
		  	<div class='panel-info'>
			  	<div class='title pull-left'>NEW EQUIPMENT</div>
				<div class='modify pull-right'>LAST MODIFIED -, BY -</div>
			</div>
			<div class='separator'></div>";
			
			if (!empty($_SESSION["equipment"])) {
			  	echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["equipment"]."</center></div>";
				unset($_SESSION["equipment"]);
			}
			
	  echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=equipment&act=insert'>
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
							<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=equipment';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
                    </tr>
				</table>
			</form>
		  
		</div>
    </div>";
?>