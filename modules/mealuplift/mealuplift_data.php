<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("MEAL UPLIFT DATA", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>MEAL UPLIFT ENTRY</div>
		<div class='panel-page'>
		  
		  	<div class='panel-info'>
			  	<div class='title pull-left'>FINAL MEAL UPLIFT DATA</div>
			</div>
			<div class='separator'></div>
			
			<table id='dt-mealuplift' class='table table-bordered table-condensed table-striped table-hover'>
		      	<thead>
				  	<tr>
				  		<th class='span1 text-center no-sort no-search'>NO</th>
				  		<th class='span1 text-center no-visible no-search'>DATE</th>
					  	<th class='text-center' style='width:100px;'>DATE</th>
					  	<th class='text-center' style='width:80px;'>AIRLINE</th>
					  	<th class='text-center' style='width:120px;'>TOTAL FLIGHT</th>
					  	<th class='text-center' style='width:80px;'>TOTAL F/C</th>
					  	<th class='text-center' style='width:80px;'>TOTAL B/C</th>
					  	<th class='text-center' style='width:80px;'>TOTAL Y/C</th>
					  	<th class='text-center' style='width:80px;'>TOTAL C/R</th>
					  	<th class='text-center' style='width:80px;'>TOTAL C/P</th>
					  	<th class='text-center' style='width:120px;'>REMAIN FLIGHT</th>
					  	<th class='text-center no-sort no-search' style='width:75px;'>ACTION</th>
				  	</tr>
				</thead>
				
				<tfoot>
				  	<tr>
				  		<th class='span1 text-center'>NO</th>
				  		<th class='span1 text-center'>DATE</th>
					  	<th class='text-center' style='width:100px;'>DATE</th>
					  	<th class='text-center' style='width:80px;'>AIRLINE</th>
					  	<th class='text-center' style='width:120px;'>TOTAL FLIGHT</th>
					  	<th class='text-center' style='width:80px;'>TOTAL F/C</th>
					  	<th class='text-center' style='width:80px;'>TOTAL B/C</th>
					  	<th class='text-center' style='width:80px;'>TOTAL Y/C</th>
					  	<th class='text-center' style='width:80px;'>TOTAL C/R</th>
					  	<th class='text-center' style='width:80px;'>TOTAL C/P</th>
					  	<th class='text-center' style='width:120px;'>REMAIN FLIGHT</th>
					  	<th class='text-center' style='width:75px;'>ACTION</th>
				  	</tr>
				</tfoot>
		 	</table>
			
		</div>
	</div>";
?>