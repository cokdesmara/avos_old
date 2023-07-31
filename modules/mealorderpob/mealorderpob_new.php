<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
	$action = "modules/mealorderpob/mealorderpob_action.php";
	$date = $secure->sanitize($_GET["date"]);
	
	$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
	$breadcrumb->append_crumb("MEAL ORDER & P.O.B DATA", "index.php?page=mealorderpob");
	$breadcrumb->append_crumb("NEW MEAL ORDER & P.O.B", "#");
	echo $breadcrumb->breadcrumb();
	
	$query_user = $mysqli->query("select t_user.name as user, t_meal_order_pob.modified as modified from t_meal_order_pob left join t_header on t_meal_order_pob.header = t_header.id left join t_user on t_meal_order_pob.user = t_user.id where t_header.date = '".$datetime->database_date($date)."' order by t_meal_order_pob.modified desc limit 0, 1");
	$u = $query_user->fetch_assoc();
	
	$day = $datetime->get_day($datetime->database_date($date));
	$modified = !empty($u["modified"]) ? $datetime->indonesian_datetime($u["modified"]) : "-";
	$user = !empty($u["user"]) ? $u["user"] : "-";
	
	echo "<div class='panel'>
			<div class='panel-label'>MEAL ORDER & P.O.B ENTRY</div>
		  	<div class='panel-page'>
		  		
			  	<div class='panel-info'>
				  	<div class='title pull-left'>NEW FINAL MEAL ORDER & P.O.B</div>
					<div class='modify pull-right'>LAST MODIFIED ".$modified.", BY ".$user."</div>
				</div>
				<div class='separator'></div>
				
				<div class='form-horizontal'>
					<table class='field-table'>
					  	<tr>
							<th><label for='name'>DATE</label></th>
							<td style='width:20px;'>:</td>
							<td>".$day.", ".$date."</td>
						</tr>
						<tr>
	                        <td colspan='3'><div class='separator'></div></td>
	                    </tr>
	                </table>
	                
	                <div class='pull-left'>
            			<button type='button' class='btn btn-inverse' onclick=\"window.location.href='index.php?page=mealorderpob';\"><i class='icon-arrow-left icon-white'></i> BACK</button>&nbsp;&nbsp;&nbsp;
            			<button type='button' class='btn btn-success' onclick=\"NewMealOrderPOB()\"><i class='icon-plus icon-white'></i> ADD</button>
            		</div>
	            	
					<table id='dt-mealorderpob-new' class='table table-bordered table-condensed table-striped table-hover'>
						<thead>
						  	<tr>
						  		<th rowspan='2' class='span1 text-center no-sort no-search'>NO</th>
								<th rowspan='2' class='span1 text-center no-visible no-search'>ID</th>
								<th rowspan='2' class='span1 text-center no-visible no-search'>ID</th>
								<th rowspan='2' class='text-center no-visible no-search' style='width:150px;'>FLIGHT</th>
								<th rowspan='2' class='text-center no-visible no-search' style='width:150px;'>FLIGHT</th>
								<th rowspan='2' class='text-center' style='width:150px;'>FLIGHT</th>
								<th rowspan='2' class='text-center no-visible no-search' style='width:100px;'>STATUS</th>
								<th rowspan='2' class='text-center no-visible no-search' style='width:100px;'>STATUS</th>
								<th rowspan='2' class='text-center no-visible no-search' style='width:150px;'>CONFIG</th>
								<th rowspan='2' class='text-center' style='width:150px;'>CONFIG</th>
								<th rowspan='2' class='text-center no-visible no-search' style='width:100px;'>AIRCRAFT</th>
								<th rowspan='2' class='text-center no-visible no-search' style='width:100px;'>EQUIPMENT</th>
								<th colspan='5' class='text-center no-visible no-search' style='width:250px;'>SEAT</th>
						  		<th colspan='5' class='text-center' style='width:250px;'>MEAL ORDER</th>
						  		<th colspan='5' class='text-center' style='width:250px;'>PAX ON BOARD</th>
						  		<th colspan='3' class='text-center' style='width:150px;'>SPML</th>
							  	<th rowspan='2' class='text-center no-sort no-search' style='width:150px;'>ACTION</th>
						  	</tr>
						  	<tr>
						  		<th class='text-center no-visible no-search' style='width:50px;'>F/C</th>
							  	<th class='text-center no-visible no-search' style='width:50px;'>B/C</th>
							  	<th class='text-center no-visible no-search' style='width:50px;'>Y/C</th>
							  	<th class='text-center no-visible no-search' style='width:50px;'>C/R</th>
							  	<th class='text-center no-visible no-search' style='width:50px;'>C/P</th>
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
						  	</tr>
						</thead>
						
						<tfoot>
						  	<tr>
						  		<th rowspan='2' class='span1 text-center'>NO</th>
								<th rowspan='2' class='span1 text-center'>ID</th>
								<th rowspan='2' class='span1 text-center'>ID</th>
								<th rowspan='2' class='text-center' style='width:150px;'>FLIGHT</th>
								<th rowspan='2' class='text-center' style='width:150px;'>FLIGHT</th>
								<th rowspan='2' class='text-center' style='width:150px;'>FLIGHT</th>
								<th rowspan='2' class='text-center' style='width:100px;'>STATUS</th>
								<th rowspan='2' class='text-center' style='width:100px;'>STATUS</th>
								<th rowspan='2' class='text-center' style='width:150px;'>CONFIG</th>
								<th rowspan='2' class='text-center' style='width:150px;'>CONFIG</th>
								<th rowspan='2' class='text-center' style='width:100px;'>AIRCRAFT</th>
								<th rowspan='2' class='text-center' style='width:100px;'>EQUIPMENT</th>
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
							  	<th class='text-center' style='width:50px;'>F/C</th>
							  	<th class='text-center' style='width:50px;'>B/C</th>
							  	<th class='text-center' style='width:50px;'>Y/C</th>
							  	<th class='text-center' style='width:50px;'>C/R</th>
							  	<th class='text-center' style='width:50px;'>C/P</th>
							  	<th class='text-center' style='width:50px;'>BBML</th>
							  	<th class='text-center' style='width:50px;'>KSML</th>
							  	<th class='text-center' style='width:50px;'>HCAKE</th>
							  	<th rowspan='2' class='text-center no-sort no-search' style='width:150px;'>ACTION</th>
						  	</tr>
						  	<tr>
						  		<th colspan='5' class='text-center' style='width:250px;'>SEAT</th>
						  		<th colspan='5' class='text-center' style='width:250px;'>MEAL ORDER</th>
						  		<th colspan='5' class='text-center' style='width:250px;'>PAX ON BOARD</th>
						  		<th colspan='3' class='text-center' style='width:150px;'>SPML</th>
						  	</tr>
						</tfoot>
				 	</table>
				</div>
				
			</div>
	    </div>
	    
	    <div id='modal-mealorderpob-new' class='modal hide fade form' tabindex='-1' role='dialog' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
		  	<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4>FINAL MEAL ORDER & P.O.B</h4>
			</div>
			<form id='form-mealorderpob-new'>
				<div class='modal-body'>
					<div class='form-horizontal' style='margin-bottom:10px;'>
						<input type='hidden' id='id' name='id' />
				  		<input type='hidden' id='header' name='header' />
				  		<input type='hidden' id='date' name='date' value='".$date."' />
				  		<input type='hidden' id='ori_flight' name='ori_flight' />
				  		<input type='hidden' id='ori_status' name='ori_status' />
						<table class='field-table'>
							<tr>
								<th><label>DATE</label></th>
								<td style='width:20px;'>:</td>
								<td>".$day.", ".$date."</td>
							</tr>
							<tr>
								<th><label for='flight'>FLIGHT <span class='red'>*</span></label></th>
								<td>:</td>
								<td><input type='text' id='flight' name='flight' class='input-medium input-upper flight' data-placeholder='-- CHOOSE FLIGHT --' required />
									<input type='text' id='status' name='status' class='input-upper status' style='width:180px;' data-placeholder='-- CHOOSE STATUS --' required /></td>
							</tr>
							<tr>
								<th><label for='config'>CONFIG <span class='red'>*</span></label></th>
								<td>:</td>
								<td><input type='text' id='config' name='config' class='input-medium input-upper config config-select' data-placeholder='-- CHOOSE CONFIG --' required />
									<div class='input-prepend'><span class='add-on'>A/C</span><input type='text' id='aircraft' name='aircraft' class='input-small input-upper' placeholder='X' disabled /></div>
									<div class='input-prepend'><span class='add-on'>E/Q</span><input type='text' id='equipment' name='equipment' class='input-small input-upper' placeholder='X' disabled /></div>
								</td>
							</tr>
							<tr>
								<th><label for='seatfc'>SEAT</label></th>
								<td>:</td>
								<td><div class='input-prepend'><span class='add-on'>F/C</span><input type='text' id='seatfc' name='seatfc' class='input-mini text-right input-upper decimal-mask' placeholder='X' disabled /></div>
									<div class='input-prepend'><span class='add-on'>B/C</span><input type='text' id='seatbc' name='seatbc' class='input-mini text-right input-upper decimal-mask' placeholder='X' disabled /></div>
									<div class='input-prepend'><span class='add-on'>Y/C</span><input type='text' id='seatyc' name='seatyc' class='input-mini text-right input-upper decimal-mask' placeholder='X' disabled /></div>
									<div class='input-prepend'><span class='add-on'>C/R</span><input type='text' id='seatcr' name='seatcr' class='input-mini text-right input-upper decimal-mask' placeholder='X' disabled /></div>
									<div class='input-prepend'><span class='add-on'>C/P</span><input type='text' id='seatcp' name='seatcp' class='input-mini text-right input-upper decimal-mask' placeholder='X' disabled /></div>
								</td>
							</tr>
						  	<tr>
								<th><label for='mofc'>MEAL ORDER <span class='red'>*</span></label></th>
								<td>:</td>
								<td><div class='input-prepend'><span class='add-on'>F/C</span><input type='text' id='mofc' name='mofc' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
									<div class='input-prepend'><span class='add-on'>B/C</span><input type='text' id='mobc' name='mobc' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
									<div class='input-prepend'><span class='add-on'>Y/C</span><input type='text' id='moyc' name='moyc' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
									<div class='input-prepend'><span class='add-on'>C/R</span><input type='text' id='mocr' name='mocr' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
									<div class='input-prepend'><span class='add-on'>C/P</span><input type='text' id='mocp' name='mocp' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
								</td>
							</tr>
							<tr>
								<th><label for='pobfc'>PAX ON BOARD <span class='red'>*</span></label></th>
								<td>:</td>
								<td><div class='input-prepend'><span class='add-on'>F/C</span><input type='text' id='pobfc' name='pobfc' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
									<div class='input-prepend'><span class='add-on'>B/C</span><input type='text' id='pobbc' name='pobbc' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
									<div class='input-prepend'><span class='add-on'>Y/C</span><input type='text' id='pobyc' name='pobyc' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
									<div class='input-prepend'><span class='add-on'>C/R</span><input type='text' id='pobcr' name='pobcr' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
									<div class='input-prepend'><span class='add-on'>C/P</span><input type='text' id='pobcp' name='pobcp' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
								</td>
							</tr>
							<tr>
								<th><label for='bbml'>SPML <span class='red'>*</span></label></th>
								<td>:</td>
								<td><div class='input-prepend'><span class='add-on'>BBML</span><input type='text' id='bbml' name='bbml' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
									<div class='input-prepend'><span class='add-on'>KSML</span><input type='text' id='ksml' name='ksml' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
									<div class='input-prepend'><span class='add-on'>HCAKE</span><input type='text' id='hcake' name='hcake' class='input-mini text-right input-upper decimal-mask' placeholder='X' required /></div>
								</td>
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
	   	</div>
		
	    <div id='modal-mealorderpob-delete' class='modal hide fade message' tabindex='-1' role='dialog' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
		  	<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4>ATTENTION</h4>
			</div>
			<form id='form-mealorderpob-delete'>
				<div class='modal-body'>
					<div class='form-horizontal' style='margin-bottom:10px;'>
						<input type='hidden' id='header_delete' name='header_delete' />
						<p class='text-center'>ARE YOU SURE TO DELETE THIS RECORDS ?</p>
					</div>
					<div id='result-delete'></div>
				</div>
				<div class='modal-footer'>
					<div class='pull-right'>
						<button id='yes' type='submit' class='btn btn-success'><i class='icon-ok icon-white'></i> YES</button>&nbsp;
						<button id='no' type='button' class='btn btn-inverse' data-dismiss='modal' aria-hidden='true'><i class='icon-remove icon-white'></i> NO</button>
					</div>
					<div id='loading-delete' class='pull-right' style='padding:5px;'></div>
				</div>
			</form>
	   	</div>";
} else {
	include "errors/401.php";
}
?>