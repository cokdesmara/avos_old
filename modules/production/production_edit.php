<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "PRODUCTION") {
	$action = "modules/production/production_action.php";
	$date = $secure->sanitize($_GET["date"]);
	
	$query = $mysqli->query("select t_header.date as date from t_header where t_header.date = '".$datetime->database_date($date)."' group by t_header.date");
	$row = $query->num_rows;
	$r = $query->fetch_assoc();
	
	if ($row > 0) {
		$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
		$breadcrumb->append_crumb("PRODUCTION DATA", "index.php?page=production");
		$breadcrumb->append_crumb("DETAIL PRODUCTION", "index.php?page=production&act=detail&date=".$date);
		$breadcrumb->append_crumb("EDIT PRODUCTION", "#");
		echo $breadcrumb->breadcrumb();
		
		$query_user = $mysqli->query("select t_user.name as user, t_production.modified as modified from t_production left join t_header on t_production.header = t_header.id left join t_user on t_production.user = t_user.id where t_header.date = '".$datetime->database_date($date)."' order by t_production.modified desc limit 0, 1");
		$u = $query_user->fetch_assoc();
		
		$day = $datetime->get_day($r["date"]);
		$modified = !empty($u["modified"]) ? $datetime->indonesian_datetime($u["modified"]) : "-";
		$user = !empty($u["user"]) ? $u["user"] : "-";
		
		echo "<div class='panel'>
				<div class='panel-label'>PRODUCTION ENTRY</div>
			  	<div class='panel-page'>
			  		
				  	<div class='panel-info'>
					  	<div class='title pull-left'>EDIT FINAL PRODUCTION</div>
						<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$user."</div>
					</div>
					<div class='separator'></div>
					
					<div class='form-horizontal'>
					  	<table class='field-table'>
							<tr>
								<th><label>DATE</label></th>
								<td style='width:20px;'>:</td>
								<td>".$day.", ".$date."</td>
							</tr>
							<tr>
		                        <td colspan='3'><div class='separator'></div></td>
		                    </tr>
		                </table>
		                
						<div class='pull-left'>
	            			<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=production&act=detail&date=".$date."';\"><i class='icon-arrow-left icon-white'></i> BACK</button>
	            		</div>
						
						<table id='dt-production-edit' class='table table-bordered table-condensed table-striped table-hover'>
							<thead>
								<tr>
							  		<th rowspan='2' class='span1 text-center no-sort no-search'>NO</th>
									<th rowspan='2' class='span1 text-center no-visible no-search'>ID</th>
									<th rowspan='2' class='span1 text-center no-visible no-search'>ID</th>
								  	<th rowspan='2' class='text-center' style='width:150px;'>FLIGHT</th>
								  	<th rowspan='2' class='span1 text-center no-visible no-search'>STATUS</th>
								  	<th colspan='5' class='text-center' style='width:250px;'>ACTUAL MEAL</th>
								  	<th colspan='5' class='text-center' style='width:250px;'>SPARE MEAL</th>
								  	<th rowspan='2' class='text-center' style='width:100px;'>FROZEN<br/>MEAL</th>
								  	<th rowspan='2' class='text-center' style='width:100px;'>REMARK</th>
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
							  	</tr>
							</thead>
							
							<tfoot>
								<tr>
							  		<th rowspan='2' class='span1 text-center'>NO</th>
									<th rowspan='2' class='span1'>ID</th>
									<th rowspan='2' class='span1'>ID</th>
								  	<th rowspan='2' class='text-center' style='width:150px;'>FLIGHT</th>
								  	<th rowspan='2' class='span1'>STATUS</th>
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
								  	<th rowspan='2' class='text-center' style='width:100px;'>FROZEN<br/>MEAL</th>
								  	<th rowspan='2' class='text-center' style='width:100px;'>REMARK</th>
								  	<th rowspan='2' class='text-center' style='width:75px;'>ACTION</th>
							  	</tr>
							  	<tr>
								  	<th colspan='5' class='text-center' style='width:250px;'>ACTUAL MEAL</th>
								  	<th colspan='5' class='text-center' style='width:250px;'>SPARE MEAL</th>
							  	</tr>
							</tfoot>
					 	</table>
					</div>
				  
				</div>
		    </div>
		    
		    <div id='modal-production' class='modal hide fade form' tabindex='-1' role='dialog' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
			  	<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
					<h4>FINAL PRODUCTION</h4>
				</div>
				<form id='form-production'>
					<div class='modal-body'>
						<div class='form-horizontal' style='margin-bottom:10px;'>
							<input type='hidden' id='id' name='id' />
					  		<input type='hidden' id='header' name='header' />
							<table class='field-table'>
								<tr>
									<th><label>DATE</label></th>
									<td style='width:20px;'>:</td>
									<td>".$day.", ".$date."</td>
								</tr>
								<tr>
									<th><label>FLIGHT</label></th>
									<td>:</td>
									<td><div id='flight'></div></td>
								</tr>
								<tr>
									<th><label>STATUS</label></th>
									<td>:</td>
									<td><div id='status'></div></td>
								</tr>
							  	<tr>
									<th><label for='fc'>ACTUAL MEAL <span class='red'>*</span></label></th>
									<td>:</td>
									<td><div class='input-prepend'><span class='add-on'>F/C</span><input type='text' id='fc' name='fc' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
										<div class='input-prepend'><span class='add-on'>B/C</span><input type='text' id='bc' name='bc' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
										<div class='input-prepend'><span class='add-on'>Y/C</span><input type='text' id='yc' name='yc' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
										<div class='input-prepend'><span class='add-on'>C/R</span><input type='text' id='cr' name='cr' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
										<div class='input-prepend'><span class='add-on'>C/P</span><input type='text' id='cp' name='cp' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
									</td>
								</tr>
								<tr>
									<th><label for='sfc'>SPARE MEAL <span class='red'>*</span></label></th>
									<td>:</td>
									<td><div class='input-prepend'><span class='add-on'>F/C</span><input type='text' id='sfc' name='sfc' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
										<div class='input-prepend'><span class='add-on'>B/C</span><input type='text' id='sbc' name='sbc' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
										<div class='input-prepend'><span class='add-on'>Y/C</span><input type='text' id='syc' name='syc' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
										<div class='input-prepend'><span class='add-on'>C/R</span><input type='text' id='scr' name='scr' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
										<div class='input-prepend'><span class='add-on'>C/P</span><input type='text' id='scp' name='scp' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
									</td>
								</tr>
								<tr>
									<th><label for='frz'>FROZEN MEAL <span class='red'>*</span></label></th>
									<td>:</td>
									<td><div class='input-prepend'><span class='add-on'>F/Z</span><input type='text' id='frz' name='frz' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div></td>
								</tr>
							</table>
						</div>
						<div id='result'></div>
					</div>
					<div class='modal-footer'>
						<div class='pull-right'>
							<button id='save' type='submit' class='btn btn-success'><i class='icon-hdd icon-white'></i> SAVE</button>&nbsp;&nbsp;&nbsp;
							<button id='cancel' type='button' class='btn btn-inverse' data-dismiss='modal' aria-hidden='true'><i class='icon-remove icon-white'></i> CANCEL</button>
						</div>
						<div id='loading' class='pull-right' style='padding:5px;'></div>
					</div>
				</form>
		   	</div>";
	} else {
		include "errors/404.php";
	}
} else {
	include "errors/401.php";
}
?>