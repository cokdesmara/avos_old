<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("LOAD FACTOR DATA", "index.php?page=loadfactor");
$breadcrumb->append_crumb("DETAIL LOAD FACTOR", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>LOAD FACTOR REVIEW</div>
		<div class='panel-page'>
		  
		  	<div class='panel-info'>
			  	<div class='title pull-left'>DETAIL LOAD FACTOR</div>
			</div>
			<div class='separator'></div>
			
			<button type='button' class='btn btn-inverse pull-left' onclick=\"window.location.href='index.php?page=loadfactor'\"><i class='icon-arrow-left icon-white'></i> BACK</button>
			
			<table id='dt_loadfactor_detail' class='table table-bordered table-condensed table-striped table-hover'>
		      	<thead>
				  	<tr>
				  		<th rowspan='2' class='span1 text-center no-sort no-search'>NO</th>
				  		<th rowspan='2' class='text-center no-visible no-search' style='width:70px;'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:70px;'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:70px;'>AIRLINE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>AVIATION</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>TOTAL<br/>FLIGHT</th>
					  	<th colspan='6' class='text-center' style='width:300px;'>PAX ON BOARD</th>
						<th colspan='6' class='text-center' style='width:300px;'>CONFIGURATION</th>
						<th colspan='6' class='text-center' style='width:300px;'>LOAD FACTOR</th>
				  	</tr>
				  	<tr>
						<th class='text-center' style='width:50px;'>F/C</th>
						<th class='text-center' style='width:50px;'>B/C</th>
						<th class='text-center' style='width:50px;'>Y/C</th>
						<th class='text-center' style='width:50px;'>C/R</th>
						<th class='text-center' style='width:50px;'>C/P</th>
						<th class='text-center' style='width:50px;'>TTL</th>
						<th class='text-center' style='width:50px;'>F/C</th>
						<th class='text-center' style='width:50px;'>B/C</th>
						<th class='text-center' style='width:50px;'>Y/C</th>
						<th class='text-center' style='width:50px;'>C/R</th>
						<th class='text-center' style='width:50px;'>C/P</th>
						<th class='text-center' style='width:50px;'>TTL</th>
						<th class='text-center' style='width:50px;'>F/C</th>
						<th class='text-center' style='width:50px;'>B/C</th>
						<th class='text-center' style='width:50px;'>Y/C</th>
						<th class='text-center' style='width:50px;'>C/R</th>
						<th class='text-center' style='width:50px;'>C/P</th>
						<th class='text-center' style='width:50px;'>TTL</th>
					</tr>
				</thead>
				
				<tfoot>
				  	<tr>
				  		<th rowspan='2' class='span1 text-center'>NO</th>
				  		<th rowspan='2' class='text-center' style='width:70px;'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:70px;'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:70px;'>AIRLINE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>AVIATION</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>TOTAL<br/>FLIGHT</th>
						<th class='text-center' style='width:50px;'>F/C</th>
						<th class='text-center' style='width:50px;'>B/C</th>
						<th class='text-center' style='width:50px;'>Y/C</th>
						<th class='text-center' style='width:50px;'>C/R</th>
						<th class='text-center' style='width:50px;'>C/P</th>
						<th class='text-center' style='width:50px;'>TTL</th>
						<th class='text-center' style='width:50px;'>F/C</th>
						<th class='text-center' style='width:50px;'>B/C</th>
						<th class='text-center' style='width:50px;'>Y/C</th>
						<th class='text-center' style='width:50px;'>C/R</th>
						<th class='text-center' style='width:50px;'>C/P</th>
						<th class='text-center' style='width:50px;'>TTL</th>
						<th class='text-center' style='width:50px;'>F/C</th>
						<th class='text-center' style='width:50px;'>B/C</th>
						<th class='text-center' style='width:50px;'>Y/C</th>
						<th class='text-center' style='width:50px;'>C/R</th>
						<th class='text-center' style='width:50px;'>C/P</th>
						<th class='text-center' style='width:50px;'>TTL</th>
				  	</tr>
				  	<tr>
					  	<th colspan='6' class='text-center' style='width:300px;'>PAX ON BOARD</th>
						<th colspan='6' class='text-center' style='width:300px;'>CONFIGURATION</th>
						<th colspan='6' class='text-center' style='width:300px;'>LOAD FACTOR</th>
					</tr>
				</tfoot>
		 	</table>
			
		</div>
	</div>";
?>