<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("OVERCOST DATA", "index.php?page=overcost");
$breadcrumb->append_crumb("DETAIL OVERCOST", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>OVERCOST REVIEW</div>
		<div class='panel-page'>
		  
		  	<div class='panel-info'>
			  	<div class='title pull-left'>DETAIL OVERCOST</div>
			</div>
			<div class='separator'></div>
			
			<button type='button' class='btn btn-inverse pull-left' onclick=\"window.location.href='index.php?page=overcost'\"><i class='icon-arrow-left icon-white'></i> BACK</button>
			
			<table id='dt_overcost_detail' class='table table-bordered table-condensed table-striped table-hover'>
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
					  	<th colspan='6' class='text-center' style='width:300px;'>OVER SUPPLY</th>
						<th colspan='6' class='text-center' style='width:300px;'>OVER PRODUCTION</th>
						<th colspan='6' class='text-center' style='width:300px;'>WASTED MEAL</th>
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
					  	<th colspan='6' class='text-center' style='width:300px;'>OVER SUPPLY</th>
						<th colspan='6' class='text-center' style='width:300px;'>OVER PRODUCTION</th>
						<th colspan='6' class='text-center' style='width:300px;'>WASTED MEAL</th>
					</tr>
				</tfoot>
		 	</table>
			
		</div>
	</div>";
?>