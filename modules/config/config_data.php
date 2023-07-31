<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("CONFIG DATA", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>CONFIG REFERENCE</div>
		<div class='panel-page'>
		  	
		  	<div class='panel-info'>
			  	<div class='title pull-left'>CONFIG DATA</div>
			</div>
			<div class='separator'></div>
			
			<button type='button' class='btn btn-success pull-left' onclick=\"window.location.href='index.php?page=config&act=new'\"><i class='icon-plus icon-white'></i> NEW</button>
			
			<table id='dt-config' class='table table-bordered table-condensed table-striped table-hover'>
		      	<thead>
				  	<tr>
				  		<th rowspan='2' class='span1 text-center no-sort no-search'>NO</th>
				  		<th rowspan='2' class='span1 text-center no-visible no-search'>ID</th>
						<th rowspan='2' class='text-center' style='width:120px;'>REGISTER</th>
					  	<th rowspan='2' class='text-center' style='width:120px;'>AIRCRAFT</th>
					  	<th rowspan='2' class='text-center' style='width:120px;'>EQUIPMENT</th>
					  	<th colspan='5' class='text-center' style='width:400px;'>SEAT</th>
					  	<th rowspan='2' class='text-center' style='width:65px;'>ACTIVE</th>
					  	<th rowspan='2' class='text-center no-sort no-search' style='width:75px;'>ACTION</th>
				  	</tr>
				  	<tr>
				  		<th class='text-center' style='width:80px;'>F/C</th>
						<th class='text-center' style='width:80px;'>B/C</th>
						<th class='text-center' style='width:80px;'>Y/C</th>
						<th class='text-center' style='width:80px;'>C/R</th>
						<th class='text-center' style='width:80px;'>C/P</th>
					</tr>
				</thead>
				
				<tfoot>
				  	<tr>
				  		<th rowspan='2' class='span1 text-center'>NO</th>
				  		<th rowspan='2' class='span1'>ID</th>
						<th rowspan='2' class='text-center' style='width:120px;'>REGISTER</th>
					  	<th rowspan='2' class='text-center' style='width:120px;'>AIRCRAFT</th>
					  	<th rowspan='2' class='text-center' style='width:120px;'>EQUIPMENT</th>
						<th class='text-center' style='width:80px;'>F/C</th>
						<th class='text-center' style='width:80px;'>B/C</th>
						<th class='text-center' style='width:80px;'>Y/C</th>
						<th class='text-center' style='width:80px;'>C/R</th>
						<th class='text-center' style='width:80px;'>C/P</th>
					  	<th rowspan='2' class='text-center' style='width:65px;'>ACTIVE</th>
					  	<th rowspan='2' class='text-center' style='width:75px;'>ACTION</th>
				  	</tr>
				  	<tr>
					  	<th colspan='5' class='text-center' style='width:400px;'>SEAT</th>
					</tr>
				</tfoot>
		 	</table>
			
		</div>
	</div>";
?>