<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$action = "modules/report/report_action.php";
$aviation = !empty($_GET["avt"]) ? $secure->sanitize($_GET["avt"]) : "all";
$flight = !empty($_GET["flt"]) ? $secure->sanitize($_GET["flt"]) : "all";
$no = !empty($_GET["no"]) ? strtolower($secure->sanitize($_GET["no"])) : "all";
$from = !empty($_GET["from"]) ? $secure->sanitize($_GET["from"]) : date("d/m/Y");
$to = !empty($_GET["to"]) ? $secure->sanitize($_GET["to"]) : date("d/m/Y");

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("LOAD FACTOR SUMMARY", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>LOAD FACTOR REPORT</div>
	  	<div class='panel-page'>
	  		
		  	<div class='panel-info'>
			  	<div class='title pull-left'>LOAD FACTOR SUMMARY</div>
			</div>
			<div class='separator'></div>
			
			<div class='panel-action'>
			  	<div style='display:inline-block;height:30px;'></div>
					<div class='btn-group pull-left'>
						<button class='btn btn-success' onclick=\"print_report('report');\"><i class='icon-print icon-white'></i> PRINT</button>
					    <button class='btn btn-success dropdown-toggle tips-right' data-toggle='dropdown' title='DOWNLOAD'>&nbsp;<i class='icon-download-alt icon-white'></i>&nbsp;</button>
					    <ul class='dropdown-menu'>
					    	<li><a href='javascript:void(0)' onclick=\"view_report('".$action."?page=report&act=loadfactor&avt=".$aviation."&flt=".$flight."&no=".$no."&from=".$from."&to=".$to."');\"><i class='icon-fullscreen icon-black'></i> VIEW</a></li>
							<li class='divider'></li>
							<li><a href='javascript:void(0)' onclick=\"window.location.href='".$action."?page=report&act=loadfactor&avt=".$aviation."&flt=".$flight."&no=".$no."&from=".$from."&to=".$to."&export=word'\"><i class='icon-file icon-black'></i> WORD</a></li>
							<li class='divider'></li>
					    	<li><a href='javascript:void(0)' onclick=\"window.location.href='".$action."?page=report&act=loadfactor&avt=".$aviation."&flt=".$flight."&no=".$no."&from=".$from."&to=".$to."&export=excel'\"><i class='icon-file icon-black'></i> EXCEL</a></li>
					    </ul>
				    </div>
					<form class='form-inline form-search pull-right' method='GET' action='".$_SERVER["PHP_SELF"]."'>
				  		<input type='hidden' name='page' value='report'>
						<input type='hidden' name='act' value='loadfactor'>
						<label class='control-label' for='avt'>AVT : </label>&nbsp;
						<select id='avt' name='avt' class='select2-single input-small'>
							<option value='all' selected>ALL</option>";
       			 			$list_aviation = $mysqli->query("select code, name from t_aviation group by code order by id asc");
	          			  	while($a = $list_aviation->fetch_assoc()) {
								if ($aviation == strtolower($a["name"])) {
									echo "<option value='".strtolower($a["name"])."' selected>".$a["code"]."</option>";
								} else {
									echo "<option value='".strtolower($a["name"])."'>".$a["code"]."</option>";
								}
							}
				      	echo "</select>&nbsp;
				      	<label class='control-label' for='flt'>FLT : </label>&nbsp;
				      	<select id='flt' name='flt' class='select2-single input-small'>
							<option value='all' selected>ALL</option>";
       			 			$list_flight = $mysqli->query("select code from t_airline group by code order by id asc");
	          			  	while($f = $list_flight->fetch_assoc()) {
								if ($flight == strtolower($f["code"])) {
									echo "<option value='".strtolower($f["code"])."' selected>".$f["code"]."</option>";
								} else {
									echo "<option value='".strtolower($f["code"])."'>".$f["code"]."</option>";
								}
							}
				      	echo "</select>&nbsp;
				      	<label class='control-label' for='no'>NO : </label>&nbsp;
				      	<input type='text' id='no' name='no' class='input-upper text-center' style='width:50px;' placeholder='ALL' value='".preg_replace('/\ball\b/u', "", $no)."' />&nbsp;
				  		<label class='control-label' for='from'>FROM : </label>&nbsp;
						<div id='dp1' class='input-append date datepicker'>
							<input type='text' id='from' name='from' class='input-small' value='".$from."' /><span class='add-on'><i class='icon-calendar'></i></span>
						</div>&nbsp;
						<label class='control-label' for='to'>TO : </label>&nbsp;
						<div id='dp2' class='input-append date datepicker'>
							<input type='text' id='to' name='to' class='input-small' value='".$to."' /><span class='add-on'><i class='icon-calendar'></i></span>
						</div>&nbsp;
						<button type='submit' class='btn btn-success'><i class='icon-search icon-white'></i> FIND</button>
				  	</form>
			  	</div>
				
				<iframe id='report' class='report' src='".$action."?page=report&act=loadfactor&avt=".$aviation."&flt=".$flight."&no=".$no."&from=".$from."&to=".$to."' frameborder='0'></iframe>
				
		  	</div>
      	</div>";
?>