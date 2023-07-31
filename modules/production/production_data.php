<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("PRODUCTION DATA", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>PRODUCTION ENTRY</div>
		<div class='panel-page'>
		  
		  	<div class='panel-info'>
			  	<div class='title pull-left'>FINAL PRODUCTION DATA</div>
			</div>
			<div class='separator'></div>
			
			<table id='dt-production' class='table table-bordered table-condensed table-striped table-hover'>
		      	<thead>
				  	<tr>
				  		<th rowspan='2' class='span1 text-center no-sort no-search'>NO</th>
				  		<th rowspan='2' class='span1 text-center no-visible no-search'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>TOTAL<br/>FLIGHT</th>
					  	<th colspan='5' class='text-center' style='width:250px;'>TOTAL ACTUAL</th>
					  	<th colspan='5' class='text-center' style='width:250px;'>TOTAL SPARE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>TOTAL<br/>FROZEN</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>REMAIN<br/>FLIGHT</th>
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
				  		<th rowspan='2' class='span1 text-center'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>DATE</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>TOTAL<br/>FLIGHT</th>
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
					  	<th rowspan='2' class='text-center' style='width:100px;'>TOTAL<br/>FROZEN</th>
					  	<th rowspan='2' class='text-center' style='width:100px;'>REMAIN<br/>FLIGHT</th>
					  	<th rowspan='2' class='text-center' style='width:75px;'>ACTION</th>
				  	</tr>
				  	<tr>
					  	<th colspan='5' class='text-center' style='width:250px;'>TOTAL ACTUAL</th>
					  	<th colspan='5' class='text-center' style='width:250px;'>TOTAL SPARE</th>
				  	</tr>
				</tfoot>
		 	</table>
			
		</div>
	</div>";
?>