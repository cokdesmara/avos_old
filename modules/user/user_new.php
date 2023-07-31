<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/user/user_action.php";
$list_privilege = array(array("ADMINISTRATOR", "ADMINISTRATOR"), array("ORDER CENTER", "ORDER CENTER"), array("PRODUCTION", "PRODUCTION"), array("OPERATION GA", "OPERATION GA"), array("OPERATION NONGA", "OPERATION NONGA"), array("MONITORING", "MONITORING"));

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("USER DATA", "index.php?page=user");
$breadcrumb->append_crumb("NEW USER", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>USER MANAGEMENT</div>
	  	<div class='panel-page'>
	  		
		  	<div class='panel-info'>
			  	<div class='title pull-left'>NEW USER</div>
				<div class='modify pull-right'>LAST MODIFIED -, BY -</div>
			</div>
			<div class='separator'></div>";
			
			if (!empty($_SESSION["user"])) {
			  	echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>".$_SESSION["user"]."</center></div>";
				unset($_SESSION["user"]);
			}
			
	  echo "<form class='form-horizontal form-validate' method='POST' action='".$action."?page=user&act=insert'>
				<table class='field-table'>
				  	<tr>
						<th><label for='name'>NAME <span class='red'>*</span></label></th>
						<td style='width:20px;'>:</td>
						<td><input type='text' id='name' name='name' class='input-xlarge input-upper validate(required, maxlength(100))' autofocus /></td>
					</tr>
					<tr>
						<th><label for='email'>E-MAIL <span class='red'>*</span></label></th>
						<td>:</td>
						<td><input type='text' id='email' name='email' class='input-large input-upper validate(required, email, maxlength(50))' autocomplete='off' /></td>
					</tr>
					<tr>
						<th><label for='password'>PASSWORD <span class='red'>*</span></label></th>
						<td>:</td>
						<td><input type='password' id='password' name='password' class='input-medium input-upper validate(required, maxlength(50))' autocomplete='off' /></td>
					</tr>
					<tr>
						<th><label for='privilege'>PRIVILEGE <span class='red'>*</span></label></th>
						<td>:</td>
						<td><select id='privilege' name='privilege' class='select2-single input-large validate(required)' data-placeholder='-- CHOOSE PRIVILEGE --'>
							<option value='' selected></option>";
							foreach ($list_privilege as $value) echo "<option value='".$value["0"]."'>".$value["1"]."</option>";
				        echo "</select></td>
					</tr>
					<tr>
						<th><label for='active'>ACTIVE</label></th>
						<td>:</td>
						<td><label class='radio inline'><input type='radio' id='active' name='active' value='Y' checked /> YES</label>&nbsp;&nbsp;&nbsp;
							<label class='radio inline'><input type='radio' id='active' name='active' value='N' /> NO</label></td>
					</tr>
					<tr>
						<th><label>LAST LOGIN</label></th>
						<td>:</td>
						<td>-</td>
					</tr>
					<tr>
						<th><label>LOGIN TIME</label></th>
						<td>:</td>
						<td>-</td>
					</tr>
					<tr>
                        <td colspan='3'><div class='separator'></div></td>
                    </tr>
					<tr>
                        <th></th>
                        <td></td>
                        <td><button type='submit' class='btn btn-success'><i class='icon-hdd icon-white'></i> SAVE</button>&nbsp;&nbsp;&nbsp;
							<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=user';\"><i class='icon-remove icon-white'></i> CANCEL</button></td>
                    </tr>
				</table>
			</form>
		  
		</div>
    </div>";
?>