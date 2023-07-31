<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

$breadcrumb->append_crumb("DASHBOARD", "index.php?page=dashboard");
$breadcrumb->append_crumb("EQUIPMENT DATA", "#");
echo $breadcrumb->breadcrumb();

echo "<div class='panel'>
		<div class='panel-label'>EQUIPMENT REFERENCE</div>
		<div class='panel-page'>
		  
		  	<div class='panel-info'>
			  	<div class='title pull-left'>EQUIPMENT DATA</div>
			</div>
			<div class='separator'></div>
			
			<button type='button' class='btn btn-success pull-left' onclick=\"window.location.href='index.php?page=equipment&act=new'\"><i class='icon-plus icon-white'></i> NEW</button>
			
			<table id='dt-equipment' class='table table-bordered table-condensed table-striped table-hover'>
		      	<thead>
				  	<tr>
				  		<th class='span1 text-center no-sort no-search'>NO</th>
				  		<th class='span1 text-center no-visible no-search'>ID</th>
						<th>NAME</th>
					  	<th class='text-center' style='width:65px;'>ACTIVE</th>
					  	<th class='text-center no-sort no-search' style='width:75px;'>ACTION</th>
				  	</tr>
				</thead>
				
				<tfoot>
				  	<tr>
				  		<th class='span1 text-center'>NO</th>
				  		<th class='span1'>ID</th>
						<th>NAME</th>
					  	<th class='text-center' style='width:65px;'>ACTIVE</th>
					  	<th class='text-center' style='width:75px;'>ACTION</th>
				  	</tr>
				</tfoot>
		 	</table>
			
		</div>
	</div>";
?>