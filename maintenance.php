<?php define("RESTRICTED", 1); ?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
	<head>
		<title>AVOS - MAINTENANCE</title>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
		<meta http-equiv='X-UA-Compatible' content='IE=edge, chrome=1'>
		<meta http-equiv='cache-control' content='max-age=0, no-cache, no-store, must-revalidate, post-check=0, pre-check=0' />
		<meta http-equiv='pragma' content='no-cache' />
		<meta http-equiv='expires' content='-1' />
		<meta http-equiv='expires' content='Tue, 01 Jan 1980 1:00:00 GMT' />
		<meta http-equiv='copyright' content='PT. AEROFOOD INDONESIA - ACS DENPASAR. ALL RIGHTS RESERVED.' />
		<meta name='author' content='COKORDA GDE AGUNG SMARA ADNYANA PUTRA' />
		<meta name='contact' content='COKORDA.SMARA@GMAIL.COM' />
    	<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1' />
    	<link type='image/x-icon' rel='shortcut icon' href='assets/img/favicon.ico' media='all' />
    	<link type='image/ico' rel='icon' href='assets/img/favicon.ico' media='all' />
    	<link type='text/css' rel='stylesheet' href='assets/css/bootstrap.min.css' media='all' />
    	<link type='text/css' rel='stylesheet' href='assets/css/style.css' media='all' />
    	<script type='text/javascript' src='assets/js/jquery.min.js'></script>
    	<script type='text/javascript' src='assets/js/bootstrap.min.js'></script>
	</head>
	<body>
		<div class='maintenance-page'>
			<div class='maintenance-wrapper'>
				<div class='navbar navbar-fixed-top'>
			    	<div class='navbar-inner'>
						<div class='container-fluid'>
							
							<ul class='nav'>
								<li class='divider-vertical'></li>
							  	<li><img src='assets/img/logo.png' style='margin-top:5px;' /></li>
				            	<li class='divider-vertical'></li>
							</ul>
						    <a class='brand' href='index.php?page=dashboard'>AEROFOOD OVERCOST CONTROL SYSTEM</a>
							
						    <ul class='nav pull-right'>
								<li class='divider-vertical'></li>
							    <li class='dropdown'>
								    <a class='dropdown-toggle' href='javascript:void(0)' data-toggle='dropdown'><i class='icon-cog icon-black'></i> <b class='caret'></b></a>
								    <ul class='dropdown-menu'>
										<li><a href='javascript:void(0)' role='button' data-toggle='modal' data-target='#about'><i class='icon-info-sign icon-black'></i> ABOUT</a></li>
								    </ul>
							    </li>
								<li class='divider-vertical'></li>
						    </ul>
							
				    	</div>
				    </div>
				</div>
				
				<div class='maintenance-body'>
					<div class='maintenance-box'>
						<div class='maintenance-content'>
					        <div class='maintenance-header'></div>
							<div class='maintenance-title'>UNDER MAINTENANCE</div>
							<div class='maintenance-description'>BY ICT ACS DENPASAR</div>
							<div class='copyright'><?php include "copyright.php" ?></div>
						</div>
					</div>
				</div>
				
				<div id='about' class='modal hide fade message' tabindex='-1' role='dialog' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
				  	<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
						<h4>ABOUT</h4>
					</div>
					<div class='modal-body'>
						<center>
							AEROFOOD OVERCOST CONTROL SYSTEM (AVOS)<br/>
							VERSION 4.0.0<br/><br/>
							DEVELOPED BY <a href='mailto:cokorda.smara@gmail.com' target='_blank'>COKORDA SMARA</a><br/>
							SUPPORTED BY ITC TEAM ACS DPS
						</center>
					</div>
					<div class='modal-footer'>
						<button type='button' class='btn btn-success' data-dismiss='modal' aria-hidden='true'><i class='icon-ok icon-white'></i> OK</button>
					</div>
				</div>
				
				<div class='footer navbar navbar-fixed-bottom'>
					<b class='pull-left'>AVOS</b>
					<b class='pull-right'>VERSION 4.0.0</b>
				</div>
			</div>
		</div>
	</body>
</html>