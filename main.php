<?php
define("RESTRICTED", 1);
include "includes/configuration.php";
include "includes/session.php";
include "includes/connection.php";
include "includes/secure.php";
include "includes/image.php";
?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
	<head>
		<title>AVOS - <?php include "title.php"; ?></title>
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
		<link type='text/css' rel='stylesheet' href='assets/css/bootstrap.datatables.css' media='all' />
		<link type='text/css' rel='stylesheet' href='assets/css/bootstrap.datetimepicker.min.css' media='all' />
		<link type='text/css' rel='stylesheet' href='assets/css/datatables.scroller.min.css' media='all' />
		<link type='text/css' rel='stylesheet' href='assets/css/jquery.ketchup.css' media='all' />
		<link type='text/css' rel='stylesheet' href='assets/css/select2.bootstrap.css' media='all' />
		<link type='text/css' rel='stylesheet' href='assets/css/select2.css' media='all' />
		<link type='text/css' rel='stylesheet' href='assets/css/style.css' media='all' />
	</head>
	<body>
		
		<?php if (empty($_SESSION["user_session"])) { ?>
			
			<div class='login-page'>
				<div class='login-wrapper'>
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
					
					<div class='login-body'>
						<div class='login-box'>
							<div class='login-content'>
						        <div class='login-header'></div>
								<div class='login-notice'>FOR REGISTRATION PLEASE CONTACT YOUR ADMINISTRATOR SYSTEM</div>
								<form class='login-form form-horizontal form-validate'>
									<table class='login-table'>
										<tr>
											<td colspan='3'><div class='login-title'>USER LOGIN</div></td>
										</tr>
									  	<tr>
											<th><label for='email'>E-MAIL</label></th>
											<td style='width:20px;'>:</td>
											<td><input type='text' id='email' name='email' class='input-large input-upper validate(required, email, maxlength(50)) ketchup-no-margin' autofocus /></td>
										</tr>
										<tr>
											<th><label for='password'>PASSWORD</label></th>
											<td>:</td>
											<td><input type='password' id='password' name='password' class='input-large input-upper validate(required, maxlength(50)) ketchup-no-margin' /></td>
										</tr>
										<tr>
											<td colspan='3'>
												<div class='pull-right'>
													<button type='submit' class='btn btn-success'><i class='icon-lock icon-white'></i> LOGIN</button>&nbsp;
													<button type='reset' class='btn btn-inverse'><i class='icon-remove icon-white'></i> RESET</button>
												</div>
												<div id='loading' class='pull-right' style='margin-top:7px;'></div>
											</td>
										</tr>
									</table>
								</form>
								<div id='result'></div>
								<div class='copyright'><?php include "copyright.php" ?></div>
							</div>
						</div>
					</div>
					
					<div class='footer navbar navbar-fixed-bottom'>
						<b class='pull-left'>AVOS</b>
						<b class='pull-right'>VERSION 4.0.0</b>
					</div>
				</div>
			</div>
			
		<?php } else { ?>
		 	
	 		<div class='wrapper'>
				<div class='navbar navbar-fixed-top'>
			    	<div class='navbar-inner'>
						<div class='container-fluid'>
							
							<ul class='nav'>
								<li class='divider-vertical'></li>
							  	<li><img src='assets/img/logo.png' style='margin:5px 5px 0px 5px;' /></li>
				            	<li class='divider-vertical'></li>
							</ul>
						    <a class='brand' href='index.php?page=dashboard'>AEROFOOD OVERCOST CONTROL SYSTEM</a>
							
						    <ul class='nav pull-right'>
								<li class='divider-vertical'></li>
								<li class='hidden'><a class='tips' href='index.php?page=account' title='<?php echo $_SESSION["user_email"]; ?>'>WELCOME, <b><?php echo $_SESSION["user_name"]; ?></b></a></li>
								<li class='divider-vertical'></li>
							    <li class='dropdown'>
								    <a class='dropdown-toggle' href='javascript:void(0)' data-toggle='dropdown'><i class='icon-cog icon-black'></i> <b class='caret'></b></a>
								    <ul class='dropdown-menu'>
										<li><a href='index.php?page=account'><i class='icon-user icon-black'></i> ACCOUNT</a></li>
										<li class='divider'></li>
								    	<li><a href='javascript:void(0)' onclick="window.location.href='logout.php'"><i class='icon-off icon-black'></i> LOGOUT</a></li>
								    </ul>
							    </li>
								<li class='divider-vertical'></li>
								<li><a class='tips' href='javascript:void(0)' onclick="window.location.reload();" title='REFRESH'><i class='icon-refresh icon-black'></i></a></li>
								<li class='divider-vertical'></li>
						    </ul>
							
				    	</div>
				    </div>
				</div>
				
				<div class='affix-sidebar sidebar pull-left' data-spy='affix' data-smooth-scroll='true'>
				    <ul id='sidenav01' class='accordion nav sidenav'>
				        <?php include "menu.php"; ?>
				    </ul>
				</div>
				
				<div class='content'>
					<div class='main'>
						<?php include "content.php"; ?>
					</div>
				</div>
			</div>
			
		<?php } ?>
		
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
		
		<script type='text/javascript' src='assets/js/jquery.min.js'></script>
		<script type='text/javascript' src='assets/js/bootstrap.min.js'></script>
		<script type='text/javascript' src='assets/js/bootstrap.datetimepicker.min.js'></script>
		<script type='text/javascript' src='assets/js/jquery.ketchup.js'></script>
		<script type='text/javascript' src='assets/js/jquery.ketchup.messages.js'></script>
		<script type='text/javascript' src='assets/js/jquery.ketchup.validations.basic.js'></script>
		<script type='text/javascript' src='assets/js/jquery.mask.min.js'></script>
		<script type='text/javascript' src='assets/js/jquery.datatables.min.js'></script>
		<script type='text/javascript' src='assets/js/jquery.tabletools.min.js'></script>
		<script type='text/javascript' src='assets/js/bootstrap.datatables.js'></script>
		<script type='text/javascript' src='assets/js/select2.min.js'></script>
		<script type='text/javascript' src='assets/js/bootstrap.select2.js'></script>
		<script type='text/javascript' src='assets/js/holder.js'></script>
		<script type='text/javascript' src='assets/js/main.script.js'></script>
	</body>
</html>