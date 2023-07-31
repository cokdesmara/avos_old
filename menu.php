<?php if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !"); ?>

<?php if ($_SESSION["user_privilege"] == "ADMINISTRATOR") { ?>
	<li>
	    <a href='index.php?page=dashboard'><i class='icon-home icon-black'></i> DASHBOARD</a> 
	</li>
	<li>
	    <a href='index.php?page=user'><i class='icon-user icon-black'></i> USER</a> 
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav01'><i class='icon-th icon-black'></i> REFERENCE <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav01' class='subnav collapse'>
	    	<li><a href='index.php?page=aviation'><i class='icon-bookmark icon-black'></i> AVIATION</a></li>
	        <li><a href='index.php?page=aircraft'><i class='icon-plane icon-black'></i> AIRCRAFT</a></li>
	        <li><a href='index.php?page=airline'><i class='icon-road icon-black'></i> AIRLINE</a></li>
	        <li><a href='index.php?page=equipment'><i class='icon-briefcase icon-black'></i> EQUIPMENT</a></li>
	        <li><a href='index.php?page=flight'><i class='icon-star icon-black'></i> FLIGHT</a></li>
	        <li><a href='index.php?page=config'><i class='icon-th-large icon-black'></i> CONFIG</a></li>
	      	<li><a href='index.php?page=status'><i class='icon-tags icon-black'></i> STATUS</a></li>
	    </ul>
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav02'><i class='icon-tasks icon-black'></i> ENTRY <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav02' class='subnav collapse'>
	        <li><a href='index.php?page=mealorderpob' ><i class='icon-list-alt icon-black'></i> MEAL ORDER & P.O.B</a></li>
	        <li><a href='index.php?page=production'><i class='icon-list-alt icon-black'></i> PRODUCTION</a></li>
	        <li><a href='index.php?page=mealuplift'><i class='icon-list-alt icon-black'></i> MEAL UPLIFT</a></li>
	    </ul>
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav03'><i class='icon-inbox icon-black'></i> REVIEW <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav03' class='subnav collapse'>
	        <li><a href='index.php?page=flightmeal' ><i class='icon-align-left icon-black'></i> FLIGHT MEAL</a></li>
	        <li><a href='index.php?page=overcost'><i class='icon-signal icon-black'></i> OVERCOST</a></li>
	        <li><a href='index.php?page=loadfactor'><i class='icon-indent-left icon-black'></i> LOAD FACTOR</a></li>
	    </ul>
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav04'><i class='icon-book icon-black'></i> REPORT <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav04' class='subnav collapse'>
	    	<li><a href='index.php?page=report&act=mealorderpob'><i class='icon-folder-open icon-black'></i> MEAL ORDER & P.O.B</a></li>
	    	<li><a href='index.php?page=report&act=production'><i class='icon-folder-open icon-black'></i> PRODUCTION</a></li>
	    	<li><a href='index.php?page=report&act=mealuplift'><i class='icon-folder-open icon-black'></i> MEAL UPLIFT</a></li>
	    	<li><a href='index.php?page=report&act=oversupply'><i class='icon-folder-open icon-black'></i> OVER SUPPLY</a></li>
	    	<li><a href='index.php?page=report&act=overproduction'><i class='icon-folder-open icon-black'></i> OVER PRODUCTION</a></li>
	    	<li><a href='index.php?page=report&act=wastedmeal'><i class='icon-folder-open icon-black'></i> WASTED MEAL</a></li>
	        <li><a href='index.php?page=report&act=overcost'><i class='icon-folder-open icon-black'></i> OVERCOST</a></li>
	        <li><a href='index.php?page=report&act=loadfactor'><i class='icon-folder-open icon-black'></i> LOAD FACTOR</a></li>
	    </ul>
	</li>
	<li>
	    <a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav05'><i class='icon-globe icon-black'></i> SYSTEM <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav05' class='subnav collapse'>
	        <li><a href='index.php?page=profile'><i class='icon-flag icon-black'></i> COMPANY PROFILE</a></li>
	        <li><a href='index.php?page=log'><i class='icon-list icon-black'></i> USER LOG</a></li>
	        <li><a href='http://10.5.2.8/avos'><i class='icon-star icon-black'></i> OLD AVOS</a> </li>
	        <li><a href='javascript:void(0)' role='button' data-toggle='modal' data-target='#about'><i class='icon-info-sign icon-black'></i> ABOUT</a></li>
	    </ul>
	</li>
<?php } elseif ($_SESSION["user_privilege"] == "ORDER CENTER") { ?>
	<li>
	    <a href='index.php?page=dashboard'><i class='icon-home icon-black'></i> DASHBOARD</a> 
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav01'><i class='icon-th icon-black'></i> REFERENCE <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav01' class='subnav collapse'>
	    	<li><a href='index.php?page=aviation'><i class='icon-bookmark icon-black'></i> AVIATION</a></li>
	        <li><a href='index.php?page=aircraft'><i class='icon-plane icon-black'></i> AIRCRAFT</a></li>
	        <li><a href='index.php?page=airline'><i class='icon-road icon-black'></i> AIRLINE</a></li>
	        <li><a href='index.php?page=equipment'><i class='icon-briefcase icon-black'></i> EQUIPMENT</a></li>
	        <li><a href='index.php?page=flight'><i class='icon-star icon-black'></i> FLIGHT</a></li>
	        <li><a href='index.php?page=config'><i class='icon-th-large icon-black'></i> CONFIG</a></li>
	      	<li><a href='index.php?page=status'><i class='icon-tags icon-black'></i> STATUS</a></li>
	    </ul>
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav02'><i class='icon-tasks icon-black'></i> ENTRY <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav02' class='subnav collapse'>
	        <li><a href='index.php?page=mealorderpob' ><i class='icon-list-alt icon-black'></i> MEAL ORDER & P.O.B</a></li>
	    </ul>
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav03'><i class='icon-inbox icon-black'></i> REVIEW <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav03' class='subnav collapse'>
	        <li><a href='index.php?page=loadfactor'><i class='icon-indent-left icon-black'></i> LOAD FACTOR</a></li>
	    </ul>
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav04'><i class='icon-book icon-black'></i> REPORT <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav04' class='subnav collapse'>
	    	<li><a href='index.php?page=report&act=mealorderpob'><i class='icon-folder-open icon-black'></i> MEAL ORDER & P.O.B</a></li>
          	<li><a href='index.php?page=report&act=wastedmeal'><i class='icon-folder-open icon-black'></i> WASTED MEAL</a></li>
	        <li><a href='index.php?page=report&act=loadfactor'><i class='icon-folder-open icon-black'></i> LOAD FACTOR</a></li>
	    </ul>
	</li>
	<li>
	    <a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav05'><i class='icon-globe icon-black'></i> SYSTEM <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav05' class='subnav collapse'>
	        <li><a href='index.php?page=profile'><i class='icon-flag icon-black'></i> COMPANY PROFILE</a></li>
	        <li><a href='http://10.5.2.8/avos'><i class='icon-star icon-black'></i> OLD AVOS</a> </li>
	        <li><a href='javascript:void(0)' role='button' data-toggle='modal' data-target='#about'><i class='icon-info-sign icon-black'></i> ABOUT</a></li>
	    </ul>
	</li>
<?php } elseif ($_SESSION["user_privilege"] == "PRODUCTION") { ?>
	<li>
	    <a href='index.php?page=dashboard'><i class='icon-home icon-black'></i> DASHBOARD</a> 
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav01'><i class='icon-tasks icon-black'></i> ENTRY <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav01' class='subnav collapse'>
	        <li><a href='index.php?page=production'><i class='icon-list-alt icon-black'></i> PRODUCTION</a></li>
	    </ul>
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav02'><i class='icon-book icon-black'></i> REPORT <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav02' class='subnav collapse'>
	    	<li><a href='index.php?page=report&act=production'><i class='icon-folder-open icon-black'></i> PRODUCTION</a></li>
	    </ul>
	</li>
	<li>
	    <a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav03'><i class='icon-globe icon-black'></i> SYSTEM <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav03' class='subnav collapse'>
	        <li><a href='index.php?page=profile'><i class='icon-flag icon-black'></i> COMPANY PROFILE</a></li>
	        <li><a href='http://10.5.2.8/avos'><i class='icon-star icon-black'></i> OLD AVOS</a> </li>
	        <li><a href='javascript:void(0)' role='button' data-toggle='modal' data-target='#about'><i class='icon-info-sign icon-black'></i> ABOUT</a></li>
	    </ul>
	</li>
<?php } elseif ($_SESSION["user_privilege"] == "OPERATION GA" or $_SESSION["user_privilege"] == "OPERATION NONGA") { ?>
	<li>
	    <a href='index.php?page=dashboard'><i class='icon-home icon-black'></i> DASHBOARD</a> 
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav01'><i class='icon-tasks icon-black'></i> ENTRY <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav01' class='subnav collapse'>
	        <li><a href='index.php?page=mealuplift'><i class='icon-list-alt icon-black'></i> MEAL UPLIFT</a></li>
	    </ul>
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav02'><i class='icon-book icon-black'></i> REPORT <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav02' class='subnav collapse'>
	    	<li><a href='index.php?page=report&act=mealuplift'><i class='icon-folder-open icon-black'></i> MEAL UPLIFT</a></li>
	    </ul>
	</li>
	<li>
	    <a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav03'><i class='icon-globe icon-black'></i> SYSTEM <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav03' class='subnav collapse'>
	        <li><a href='index.php?page=profile'><i class='icon-flag icon-black'></i> COMPANY PROFILE</a></li>
	        <li><a href='http://10.5.2.8/avos'><i class='icon-star icon-black'></i> OLD AVOS</a> </li>
	        <li><a href='javascript:void(0)' role='button' data-toggle='modal' data-target='#about'><i class='icon-info-sign icon-black'></i> ABOUT</a></li>
	    </ul>
	</li>
<?php } elseif ($_SESSION["user_privilege"] == "MONITORING") { ?>
	<li>
	    <a href='index.php?page=dashboard'><i class='icon-home icon-black'></i> DASHBOARD</a> 
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav01'><i class='icon-tasks icon-black'></i> ENTRY <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav01' class='subnav collapse'>
	        <li><a href='index.php?page=mealorderpob' ><i class='icon-list-alt icon-black'></i> MEAL ORDER & P.O.B</a></li>
	        <li><a href='index.php?page=production'><i class='icon-list-alt icon-black'></i> PRODUCTION</a></li>
	        <li><a href='index.php?page=mealuplift'><i class='icon-list-alt icon-black'></i> MEAL UPLIFT</a></li>
	    </ul>
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav02'><i class='icon-inbox icon-black'></i> REVIEW <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav02' class='subnav collapse'>
	        <li><a href='index.php?page=flightmeal' ><i class='icon-align-left icon-black'></i> FLIGHT MEAL</a></li>
	        <li><a href='index.php?page=overcost'><i class='icon-signal icon-black'></i> OVERCOST</a></li>
	        <li><a href='index.php?page=loadfactor'><i class='icon-indent-left icon-black'></i> LOAD FACTOR</a></li>
	    </ul>
	</li>
	<li>
		<a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav03'><i class='icon-book icon-black'></i> REPORT <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav03' class='subnav collapse'>
	    	<li><a href='index.php?page=report&act=mealorderpob'><i class='icon-folder-open icon-black'></i> MEAL ORDER & P.O.B</a></li>
	    	<li><a href='index.php?page=report&act=production'><i class='icon-folder-open icon-black'></i> PRODUCTION</a></li>
	    	<li><a href='index.php?page=report&act=mealuplift'><i class='icon-folder-open icon-black'></i> MEAL UPLIFT</a></li>
	    	<li><a href='index.php?page=report&act=oversupply'><i class='icon-folder-open icon-black'></i> OVER SUPPLY</a></li>
	    	<li><a href='index.php?page=report&act=overproduction'><i class='icon-folder-open icon-black'></i> OVER PRODUCTION</a></li>
	    	<li><a href='index.php?page=report&act=wastedmeal'><i class='icon-folder-open icon-black'></i> WASTED MEAL</a></li>
	        <li><a href='index.php?page=report&act=overcost'><i class='icon-folder-open icon-black'></i> OVERCOST</a></li>
	        <li><a href='index.php?page=report&act=loadfactor'><i class='icon-folder-open icon-black'></i> LOAD FACTOR</a></li>
	    </ul>
	</li>
	<li>
	    <a href='javascript:void(0)' class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#sidenav01' data-target='#subnav04'><i class='icon-globe icon-black'></i> SYSTEM <i class='icon-chevron icon-black pull-right'></i></a>
	    <ul id='subnav04' class='subnav collapse'>
	        <li><a href='index.php?page=profile'><i class='icon-flag icon-black'></i> COMPANY PROFILE</a></li>
	        <li><a href='index.php?page=log'><i class='icon-list icon-black'></i> USER LOG</a></li>
	        <li><a href='http://10.5.2.8/avos'><i class='icon-star icon-black'></i> OLD AVOS</a> </li>
	        <li><a href='javascript:void(0)' role='button' data-toggle='modal' data-target='#about'><i class='icon-info-sign icon-black'></i> ABOUT</a></li>
	    </ul>
	</li>
<?php } ?>
