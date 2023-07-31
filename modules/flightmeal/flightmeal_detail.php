<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$date = $secure->sanitize($_GET["date"]);

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("FLIGHT MEAL DATA", "index.php?page=flightmeal");
$breadcrumb->append_crumb("DETAIL FLIGHT MEAL", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>FLIGHT MEAL REVIEW</div>
		<div class='panel-page'>
		  	
		  	<div class='panel-info'>
			  	<div class='title pull-left'>DETAIL FINAL FLIGHT MEAL</div>
			</div>
			<div class='separator'></div>
			
			<button type='button' class='btn btn-inverse pull-left' onclick=\"window.location.href='index.php?page=flightmeal'\"><i class='icon-arrow-left icon-white'></i> BACK</button>
			
			<table id='dt_flightmeal_detail' class='table table-bordered table-condensed table-striped table-hover'>
		      	<thead>
				  	<tr>
				  		<th rowspan='2' class='span1 text-center no-sort no-search'>NO</th>
				  		<th rowspan='2' class='text-center no-visible no-search' style='width:70px;'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:70px;'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>AVIATION</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>FLIGHT</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>DEST</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>REG</th>
					  	<th rowspan='2' class='text-center' style='width:80px;'>A/C</th>
					  	<th rowspan='2' class='text-center' style='width:50px;'>E/Q</th>
					  	<th colspan='5' class='text-center' style='width:250px;'>MEAL ORDER</th>
						<th colspan='11' class='text-center' style='width:550px;'>PRODUCTION</th>
						<th colspan='5' class='text-center' style='width:250px;'>MEAL UPLIFT</th>
						<th colspan='5' class='text-center' style='width:250px;'>PAX ON BOARD</th>
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
						<th class='text-center' style='width:50px;'>S F/C</th>
						<th class='text-center' style='width:50px;'>S B/C</th>
						<th class='text-center' style='width:50px;'>S Y/C</th>
						<th class='text-center' style='width:50px;'>S C/R</th>
						<th class='text-center' style='width:50px;'>S C/P</th>
						<th class='text-center' style='width:50px;'>FRZ</th>
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
				  		<th rowspan='2' class='text-center' style='width:70px;'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:70px;'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>AVIATION</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>FLIGHT</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>DEST</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>REG</th>
					  	<th rowspan='2' class='text-center' style='width:80px;'>A/C</th>
					  	<th rowspan='2' class='text-center' style='width:50px;'>E/Q</th>
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
						<th class='text-center' style='width:50px;'>S F/C</th>
						<th class='text-center' style='width:50px;'>S B/C</th>
						<th class='text-center' style='width:50px;'>S Y/C</th>
						<th class='text-center' style='width:50px;'>S C/R</th>
						<th class='text-center' style='width:50px;'>S C/P</th>
						<th class='text-center' style='width:50px;'>FRZ</th>
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
				  	<tr>
					  	<th colspan='5' class='text-center' style='width:250px;'>MEAL ORDER</th>
						<th colspan='11' class='text-center' style='width:550px;'>PRODUCTION</th>
						<th colspan='5' class='text-center' style='width:250px;'>MEAL UPLIFT</th>
						<th colspan='5' class='text-center' style='width:250px;'>PAX ON BOARD</th>
					</tr>
				</tfoot>
		 	</table>
			
		</div>
	</div>";
?>