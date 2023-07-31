<?php
include "../../includes/configuration.php";

if (empty($_SESSION["user_session"])) {
	header("location:../../index.php"); 
} else {
	include "../../includes/connection.php";
	include "../../includes/secure.php";
	include "../../includes/datetime.php";
	
	$page = $_GET["page"];
	$act = $_GET["act"];
	
	if ($page == "report" and $act == "mealorderpob") {
		$export = !empty($_GET["export"]) ? $secure->sanitize($_GET["export"]) : "";
		$aviation = $_GET["avt"] != "all" ? strtoupper($secure->sanitize($_GET["avt"])) : "";
		$flight = $_GET["flt"] != "all" ? strtoupper($secure->sanitize($_GET["flt"])) : "";
		$no = $_GET["no"] != "all" ? "-".strtoupper($secure->sanitize($_GET["no"])) : "";
		$from = !empty($_GET["from"]) ? $secure->sanitize($_GET["from"]) : date("d/m/Y");
		$to = !empty($_GET["to"]) ? $secure->sanitize($_GET["to"]) : date("d/m/Y");
		$aviation_flight = strtoupper(!empty($_GET["avt"]) ? $secure->sanitize($_GET["avt"]) : "ALL");
		$code_flight = strtoupper(!empty($_GET["flt"]) ? $secure->sanitize($_GET["flt"]) : "ALL");
		$no_flight = strtoupper(!empty($_GET["no"]) ? $no : "");
		$flight_no = !empty($no_flight) ? $code_flight.$no_flight : "";
		$from_date = $datetime->database_date($from);
		$to_date = $datetime->database_date($to);
		
		$profile = $mysqli->query("select t_profile.company as company, t_profile.branch as branch from t_profile");
		$p = $profile->fetch_assoc();
		
		$header = "<header>
						<table class='header'>
							<thead>
								<tr>
								    <td width='10%'><img src='../../assets/img/aerofood.jpg' width='100px' style='margin:0px 15px;padding:5px 0px;' /></td>
									<td style='padding:0px 10px;font-weight:bold;font-size:14pt;'>FINAL MEAL ORDER & P.O.B<br> SUMMARY REPORT</td>
								</tr>
							</thead>
						</table>
						
						<table class='section'>
						  	<thead>
							  	<tr>
									<td style='width:60px;font-weight:bold;'>BRANCH</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td>".$p["branch"]."</td>
									<td style='width:70px;font-weight:bold;'>FROM DATE</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td style='width:50px;text-align:right;'>".$from."</td>
								</tr>
								<tr>
									<td style='font-weight:bold;'>AVT / FLT</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td>".$aviation_flight." / ".$code_flight."".$no_flight."</td>
									<td style='font-weight:bold;'>TO DATE</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td style='text-align:right;'>".$to."</td>
							  	</tr>
						  	</thead>
						</table>
					</header>";
		
		$footer = "<footer>
						<table class='footer'>
							<tr>
								<td colspan='2' style='border-top:1px solid #000000;vertical-align:top;'></td>
							</tr>
							<tr>
							    <td style='text-align:left;'>".$p["company"]."</td>
								<td style='text-align:right;'>AVOS</span></td>
							</tr>
						</table>
					</footer>";
		
		$html .= "<div class='content'></div><table class='table'>
				    <thead>
						<tr>
							<th rowspan='2' style='width:40px;'>NO</th>
							<th rowspan='2' style='width:70px;'>DATE</th>
							<th rowspan='2' style='width:100px;'>FLIGHT</th>
							<th rowspan='2' style='width:100px;'>DEST</th>
							<th rowspan='2' style='width:100px;'>REG</th>
							<th rowspan='2' style='width:80px;'>A/C</th>
							<th rowspan='2' style='width:50px;'>E/Q</th>
							<th colspan='5' style='width:250px;height:28px;'>MEAL ORDER</th>
							<th colspan='5' style='width:250px;height:28px;'>PAX ON BOARD</th>
							<th colspan='3' style='width:150px;height:28px;'>SPML</th>
						</tr>
						<tr>
							<th style='width:50px;height:28px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>BBML</th>
							<th style='width:50px;'>KSML</th>
							<th style='width:50px;'>HCAKE</th>
						</tr>
					</thead><tbody>";
		
		$no = 1;
		$query = $mysqli->query("select * from view_summary_overcost where date between '".$from_date."' and '".$to_date."' and aviation like '%".$aviation."%' and code like '%".$flight."%' and flight like '%".$flight_no."%'");
		$row = $query->num_rows;
		
		if ($row > 0) {
			$group = array();
			$sub_flight = array();
			$sub_meal_order_fc = array();
			$sub_meal_order_bc = array();
			$sub_meal_order_yc = array();
			$sub_meal_order_cr = array();
			$sub_meal_order_cp = array();
			$sub_pax_on_board_fc = array();
			$sub_pax_on_board_bc = array();
			$sub_pax_on_board_yc = array();
			$sub_pax_on_board_cr = array();
			$sub_pax_on_board_cp = array();
			$sub_spml_bbml = array();
			$sub_spml_ksml = array();
			$sub_spml_hcake = array();
			
			while ($r = $query->fetch_assoc()) {
				$date = $datetime->indonesian_date($r["date"]);
			    $aviation = $r["aviation"];
				
			    if(!isset($group[$aviation])){
			        $group[$aviation] = "";
			        $sub_flight[$aviation] = "0";
			        $sub_meal_order_fc[$aviation] = "0";
			        $sub_meal_order_bc[$aviation] = "0";
			        $sub_meal_order_yc[$aviation] = "0";
			        $sub_meal_order_cr[$aviation] = "0";
			        $sub_meal_order_cp[$aviation] = "0";
			        $sub_pax_on_board_fc[$aviation] = "0";
					$sub_pax_on_board_bc[$aviation] = "0";
					$sub_pax_on_board_yc[$aviation] = "0";
					$sub_pax_on_board_cr[$aviation] = "0";
					$sub_pax_on_board_cp[$aviation] = "0";
					$sub_spml_bbml[$aviation] = "0";
					$sub_spml_ksml[$aviation] = "0";
					$sub_spml_hcake[$aviation] = "0";
			        $no = 1;
			    }
				
			    $group[$aviation] .= "<tr>
										<td>".$no."</td>
										<td>".$date."</td>
										<td>".$r["flight"]."</td>
										<td>".$r["destination"]."</td>
										<td>".$r["register"]."</td>
										<td>".$r["aircraft"]."</td>
										<td>".$r["equipment"]."</td>
										<td>".$r["meal_order_fc"]."</td>
										<td>".$r["meal_order_bc"]."</td>
										<td>".$r["meal_order_yc"]."</td>
										<td>".$r["meal_order_cr"]."</td>
										<td>".$r["meal_order_cp"]."</td>
										<td>".$r["pax_on_board_fc"]."</td>
										<td>".$r["pax_on_board_bc"]."</td>
										<td>".$r["pax_on_board_yc"]."</td>
										<td>".$r["pax_on_board_cr"]."</td>
										<td>".$r["pax_on_board_cp"]."</td>
										<td>".$r["spml_bbml"]."</td>
										<td>".$r["spml_ksml"]."</td>
										<td>".$r["spml_hcake"]."</td>
									</tr>";
				
				$sub_flight[$aviation] = $no;
				$sub_meal_order_fc[$aviation] += $r["meal_order_fc"];
				$sub_meal_order_bc[$aviation] += $r["meal_order_bc"];
				$sub_meal_order_yc[$aviation] += $r["meal_order_yc"];
				$sub_meal_order_cr[$aviation] += $r["meal_order_cr"];
				$sub_meal_order_cp[$aviation] += $r["meal_order_cp"];
				$sub_pax_on_board_fc[$aviation] += $r["pax_on_board_fc"];
				$sub_pax_on_board_bc[$aviation] += $r["pax_on_board_bc"];
				$sub_pax_on_board_yc[$aviation] += $r["pax_on_board_yc"];
				$sub_pax_on_board_cr[$aviation] += $r["pax_on_board_cr"];
				$sub_pax_on_board_cp[$aviation] += $r["pax_on_board_cp"];
				$sub_spml_bbml[$aviation] += $r["spml_bbml"];
				$sub_spml_ksml[$aviation] += $r["spml_ksml"];
				$sub_spml_hcake[$aviation] += $r["spml_hcake"];
				
				$sum_flight = $row;
				$sum_meal_order_fc += $r["meal_order_fc"];
				$sum_meal_order_bc += $r["meal_order_bc"];
				$sum_meal_order_yc += $r["meal_order_yc"];
				$sum_meal_order_cr += $r["meal_order_cr"];
				$sum_meal_order_cp += $r["meal_order_cp"];
				$sum_pax_on_board_fc += $r["pax_on_board_fc"];
				$sum_pax_on_board_bc += $r["pax_on_board_bc"];
				$sum_pax_on_board_yc += $r["pax_on_board_yc"];
				$sum_pax_on_board_cr += $r["pax_on_board_cr"];
				$sum_pax_on_board_cp += $r["pax_on_board_cp"];
				$sum_spml_bbml += $r["spml_bbml"];
				$sum_spml_ksml += $r["spml_ksml"];
				$sum_spml_hcake += $r["spml_hcake"];
				
				$no++;
			}
			
			foreach($group as $aviation=>$grouping){
			    $html .= $group[$aviation];
			    $html .= "<tr style='background:#dddddd;'>
			    			<th colspan='2' style='height:28px;'>".$aviation."</th>
			    			<th>".$sub_flight[$aviation]."</th>
							<th colspan='4' style='text-align:right;'>SUB TOTAL&nbsp;&nbsp;</th>
							<th>".$sub_meal_order_fc[$aviation]."</th>
							<th>".$sub_meal_order_bc[$aviation]."</th>
							<th>".$sub_meal_order_yc[$aviation]."</th>
							<th>".$sub_meal_order_cr[$aviation]."</th>
							<th>".$sub_meal_order_cp[$aviation]."</th>
							<th>".$sub_pax_on_board_fc[$aviation]."</th>
							<th>".$sub_pax_on_board_bc[$aviation]."</th>
							<th>".$sub_pax_on_board_yc[$aviation]."</th>
							<th>".$sub_pax_on_board_cr[$aviation]."</th>
							<th>".$sub_pax_on_board_cp[$aviation]."</th>
							<th>".$sub_spml_bbml[$aviation]."</th>
							<th>".$sub_spml_ksml[$aviation]."</th>
							<th>".$sub_spml_hcake[$aviation]."</th>
						</tr>";
			}
			
			$html .= "<tr style='background:#cccccc;'>
						<th colspan='2' style='height:28px;'>ALL</th>
						<th>".$sum_flight."</th>
						<th colspan='4' style='text-align:right;'>GRAND TOTAL&nbsp;&nbsp;</th>
						<th>".$sum_meal_order_fc."</th>
						<th>".$sum_meal_order_bc."</th>
						<th>".$sum_meal_order_yc."</th>
						<th>".$sum_meal_order_cr."</th>
						<th>".$sum_meal_order_cp."</th>
						<th>".$sum_pax_on_board_fc."</th>
						<th>".$sum_pax_on_board_bc."</th>
						<th>".$sum_pax_on_board_yc."</th>
						<th>".$sum_pax_on_board_cr."</th>
						<th>".$sum_pax_on_board_cp."</th>
						<th>".$sum_spml_bbml."</th>
						<th>".$sum_spml_ksml."</th>
						<th>".$sum_spml_hcake."</th>
					</tr>";
		}
		$html .= "</tbody></table>";
		
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
			  	<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
					<head>
						<title>AVOS - MEAL ORDER & P.O.B REPORT</title>
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
				    	<link type='image/x-icon' rel='shortcut icon' href='../../assets/img/favicon.ico' media='all' />
				    	<link type='image/ico' rel='icon' href='../../assets/img/favicon.ico' media='all' />
						<style>
							.header { width:100%;margin-bottom:15px;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.header tr td { padding:2px 5px 2px 5px;border:1px solid #000000; }
						 	.section { width:100%;margin-bottom:15px;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.table { width:100%;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt;white-space:nowrap; }
							.table thead tr { background:#cccccc;border:1px solid #000000; }
							.table tbody { background:#ffffff;border:1px solid #000000; }
						 	.table tr th { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center;font-weight:bold; }
							.table tr td { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center; }
							.footer { width:100%;font-weight:bold;font-style:italic;font-size:5pt;color:#999999; }
						</style>
					</head>
					<body>".$header.$html.$footer."</body>
				</html>";
		
		$filename = "AVOS_FINAL_MEAL_ORDER_POB_SUMMARY_REPORT_".$aviation_flight."_(".$code_flight."".$no_flight.")_DATE_".$from."_TO_".$to;
		
		if ($export == "word") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-word; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".doc");
		} elseif ($export == "excel") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".xls");
		}
	} elseif ($page == "report" and $act == "production") {
		$export = !empty($_GET["export"]) ? $secure->sanitize($_GET["export"]) : "";
		$aviation = $_GET["avt"] != "all" ? strtoupper($secure->sanitize($_GET["avt"])) : "";
		$flight = $_GET["flt"] != "all" ? strtoupper($secure->sanitize($_GET["flt"])) : "";
		$no = $_GET["no"] != "all" ? "-".strtoupper($secure->sanitize($_GET["no"])) : "";
		$from = !empty($_GET["from"]) ? $secure->sanitize($_GET["from"]) : date("d/m/Y");
		$to = !empty($_GET["to"]) ? $secure->sanitize($_GET["to"]) : date("d/m/Y");
		$aviation_flight = strtoupper(!empty($_GET["avt"]) ? $secure->sanitize($_GET["avt"]) : "ALL");
		$code_flight = strtoupper(!empty($_GET["flt"]) ? $secure->sanitize($_GET["flt"]) : "ALL");
		$no_flight = strtoupper(!empty($_GET["no"]) ? $no : "");
		$flight_no = !empty($no_flight) ? $code_flight.$no_flight : "";
		$from_date = $datetime->database_date($from);
		$to_date = $datetime->database_date($to);
		
		$profile = $mysqli->query("select t_profile.company as company, t_profile.branch as branch from t_profile");
		$p = $profile->fetch_assoc();
		
		$header = "<header>
						<table class='header'>
							<thead>
								<tr>
								    <td width='10%'><img src='../../assets/img/aerofood.jpg' width='100px' style='margin:0px 15px;padding:5px 0px;' /></td>
									<td style='padding:0px 10px;font-weight:bold;font-size:14pt;'>FINAL PRODUCTION<br> SUMMARY REPORT</td>
								</tr>
							</thead>
						</table>
						
						<table class='section'>
						  	<thead>
							  	<tr>
									<td style='width:60px;font-weight:bold;'>BRANCH</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td>".$p["branch"]."</td>
									<td style='width:70px;font-weight:bold;'>FROM DATE</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td style='width:50px;text-align:right;'>".$from."</td>
								</tr>
								<tr>
									<td style='font-weight:bold;'>AVT / FLT</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td>".$aviation_flight." / ".$code_flight."".$no_flight."</td>
									<td style='font-weight:bold;'>TO DATE</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td style='text-align:right;'>".$to."</td>
							  	</tr>
						  	</thead>
						</table>
					</header>";
		
		$footer = "<footer>
						<table class='footer'>
							<tr>
								<td colspan='2' style='border-top:1px solid #000000;vertical-align:top;'></td>
							</tr>
							<tr>
							    <td style='text-align:left;'>".$p["company"]."</td>
								<td style='text-align:right;'>AVOS</span></td>
							</tr>
						</table>
					</footer>";
		
		$html .= "<div class='content'></div><table class='table'>
				    <thead>
						<tr>
							<th rowspan='2' style='width:40px;'>NO</th>
							<th rowspan='2' style='width:70px;'>DATE</th>
							<th rowspan='2' style='width:100px;'>FLIGHT</th>
							<th rowspan='2' style='width:100px;'>DEST</th>
							<th rowspan='2' style='width:100px;'>REG</th>
							<th rowspan='2' style='width:80px;'>A/C</th>
							<th rowspan='2' style='width:50px;'>E/Q</th>
							<th colspan='12' style='width:560px;height:28px;'>PRODUCTION</th>
						</tr>
						<tr>
							<th style='width:50px;height:28px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>SPARE<br/>F/C</th>
							<th style='width:50px;'>SPARE<br/>B/C</th>
							<th style='width:50px;'>SPARE<br/>Y/C</th>
							<th style='width:50px;'>SPARE<br/>C/P</th>
							<th style='width:50px;'>SPARE<br/>C/R</th>
                            <th style='width:50px;'>INHOUSE<br/>FOOD</th>
							<th style='width:50px;'>FROZEN<br/>FOOD</th>
						</tr>
					</thead><tbody>";
		
		$no = 1;
		$query = $mysqli->query("select * from view_summary_overcost where date between '".$from_date."' and '".$to_date."' and aviation like '%".$aviation."%' and code like '%".$flight."%' and flight like '%".$flight_no."%'");
		$row = $query->num_rows;
		
		if ($row > 0) {
			$group = array();
			$sub_flight = array();
			$sub_production_fc = array();
			$sub_production_bc = array();
			$sub_production_yc = array();
			$sub_production_cr = array();
			$sub_production_cp = array();
			$sub_production_spr_fc = array();
			$sub_production_spr_bc = array();
			$sub_production_spr_yc = array();
			$sub_production_spr_cr = array();
			$sub_production_spr_cp = array();
            $sub_production_inhouse = array();
			$sub_production_frozen = array();
			
			while ($r = $query->fetch_assoc()) {
				$date = $datetime->indonesian_date($r["date"]);
			    $aviation = $r["aviation"];
				
			    if(!isset($group[$aviation])){
			        $group[$aviation] = "";
			        $sub_flight[$aviation] = "0";
			        $sub_production_fc[$aviation] = "0";
					$sub_production_bc[$aviation] = "0";
					$sub_production_yc[$aviation] = "0";
					$sub_production_cr[$aviation] = "0";
					$sub_production_cp[$aviation] = "0";
					$sub_production_spr_fc[$aviation] = "0";
					$sub_production_spr_bc[$aviation] = "0";
					$sub_production_spr_yc[$aviation] = "0";
					$sub_production_spr_cr[$aviation] = "0";
					$sub_production_spr_cp[$aviation] = "0";
                    $sub_production_inhouse[$aviation] = "0";
					$sub_production_frozen[$aviation] = "0";
			        $no = 1;
			    }
				
			    $group[$aviation] .= "<tr>
										<td>".$no."</td>
										<td>".$date."</td>
										<td>".$r["flight"]."</td>
										<td>".$r["destination"]."</td>
										<td>".$r["register"]."</td>
										<td>".$r["aircraft"]."</td>
										<td>".$r["equipment"]."</td>
										<td>".$r["production_fc"]."</td>
										<td>".$r["production_bc"]."</td>
										<td>".$r["production_yc"]."</td>
										<td>".$r["production_cr"]."</td>
										<td>".$r["production_cp"]."</td>
										<td>".$r["production_spr_fc"]."</td>
										<td>".$r["production_spr_bc"]."</td>
										<td>".$r["production_spr_yc"]."</td>
										<td>".$r["production_spr_cr"]."</td>
										<td>".$r["production_spr_cp"]."</td>
                                        <td>".($r["production_yc"] - $r["production_frozen"])."</td>
										<td>".$r["production_frozen"]."</td>
									</tr>";
				
				$sub_flight[$aviation] = $no;
				$sub_production_fc[$aviation] += $r["production_fc"];
				$sub_production_bc[$aviation] += $r["production_bc"];
				$sub_production_yc[$aviation] += $r["production_yc"];
				$sub_production_cr[$aviation] += $r["production_cr"];
				$sub_production_cp[$aviation] += $r["production_cp"];
				$sub_production_spr_fc[$aviation] += $r["production_spr_fc"];
				$sub_production_spr_bc[$aviation] += $r["production_spr_bc"];
				$sub_production_spr_yc[$aviation] += $r["production_spr_yc"];
				$sub_production_spr_cr[$aviation] += $r["production_spr_cr"];
				$sub_production_spr_cp[$aviation] += $r["production_spr_cp"];
                $sub_production_inhouse[$aviation] += ($r["production_yc"] - $r["production_frozen"]);
				$sub_production_frozen[$aviation] += $r["production_frozen"];
				
				$sum_flight = $row;
				$sum_production_fc += $r["production_fc"];
				$sum_production_bc += $r["production_bc"];
				$sum_production_yc += $r["production_yc"];
				$sum_production_cr += $r["production_cr"];
				$sum_production_cp += $r["production_cp"];
				$sum_production_spr_fc += $r["production_spr_fc"];
				$sum_production_spr_bc += $r["production_spr_bc"];
				$sum_production_spr_yc += $r["production_spr_yc"];
				$sum_production_spr_cr += $r["production_spr_cr"];
				$sum_production_spr_cp += $r["production_spr_cp"];
                $sum_production_inhouse += ($r["production_yc"] - $r["production_frozen"]);
				$sum_production_frozen += $r["production_frozen"];
				
				$no++;
			}
			
			foreach($group as $aviation=>$grouping){
			    $html .= $group[$aviation];
			    $html .= "<tr style='background:#dddddd;'>
			    			<th colspan='2' style='height:28px;'>".$aviation."</th>
			    			<th>".$sub_flight[$aviation]."</th>
							<th colspan='4' style='text-align:right;'>SUB TOTAL&nbsp;&nbsp;</th>
							<th>".$sub_production_fc[$aviation]."</th>
							<th>".$sub_production_bc[$aviation]."</th>
							<th>".$sub_production_yc[$aviation]."</th>
							<th>".$sub_production_cr[$aviation]."</th>
							<th>".$sub_production_cp[$aviation]."</th>
							<th>".$sub_production_spr_fc[$aviation]."</th>
							<th>".$sub_production_spr_bc[$aviation]."</th>
							<th>".$sub_production_spr_yc[$aviation]."</th>
							<th>".$sub_production_spr_cr[$aviation]."</th>
							<th>".$sub_production_spr_cp[$aviation]."</th>
                            <th>".$sub_production_inhouse[$aviation]."</th>
							<th>".$sub_production_frozen[$aviation]."</th>
						</tr>";
			}
			
			$html .= "<tr style='background:#cccccc;'>
						<th colspan='2' style='height:28px;'>ALL</th>
						<th>".$sum_flight."</th>
						<th colspan='4' style='text-align:right;'>GRAND TOTAL&nbsp;&nbsp;</th>
						<th>".$sum_production_fc."</th>
						<th>".$sum_production_bc."</th>
						<th>".$sum_production_yc."</th>
						<th>".$sum_production_cr."</th>
						<th>".$sum_production_cp."</th>
						<th>".$sum_production_spr_fc."</th>
						<th>".$sum_production_spr_bc."</th>
						<th>".$sum_production_spr_yc."</th>
						<th>".$sum_production_spr_cr."</th>
						<th>".$sum_production_spr_cp."</th>
                        <th>".$sum_production_inhouse."</th>
						<th>".$sum_production_frozen."</th>
					</tr>";
		}
		$html .= "</tbody></table>";
		
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
			  	<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
					<head>
						<title>AVOS - PRODUCTION REPORT</title>
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
				    	<link type='image/x-icon' rel='shortcut icon' href='../../assets/img/favicon.ico' media='all' />
				    	<link type='image/ico' rel='icon' href='../../assets/img/favicon.ico' media='all' />
						<style>
							.header { width:100%;margin-bottom:15px;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.header tr td { padding:2px 5px 2px 5px;border:1px solid #000000; }
						 	.section { width:100%;margin-bottom:15px;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.table { width:100%;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt;white-space:nowrap; }
							.table thead tr { background:#cccccc;border:1px solid #000000; }
							.table tbody { background:#ffffff;border:1px solid #000000; }
						 	.table tr th { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center;font-weight:bold; }
							.table tr td { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center; }
							.footer { width:100%;font-weight:bold;font-style:italic;font-size:5pt;color:#999999; }
						</style>
					</head>
					<body>".$header.$html.$footer."</body>
				</html>";
		
		$filename = "AVOS_FINAL_PRODUCTION_SUMMARY_REPORT_".$aviation_flight."_(".$code_flight."".$no_flight.")_DATE_".$from."_TO_".$to;
		
		if ($export == "word") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-word; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".doc");
		} elseif ($export == "excel") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".xls");
		}
	} elseif ($page == "report" and $act == "mealuplift") {
		$export = !empty($_GET["export"]) ? $secure->sanitize($_GET["export"]) : "";
		$aviation = $_GET["avt"] != "all" ? strtoupper($secure->sanitize($_GET["avt"])) : "";
		$flight = $_GET["flt"] != "all" ? strtoupper($secure->sanitize($_GET["flt"])) : "";
		$no = $_GET["no"] != "all" ? "-".strtoupper($secure->sanitize($_GET["no"])) : "";
		$from = !empty($_GET["from"]) ? $secure->sanitize($_GET["from"]) : date("d/m/Y");
		$to = !empty($_GET["to"]) ? $secure->sanitize($_GET["to"]) : date("d/m/Y");
		$aviation_flight = strtoupper(!empty($_GET["avt"]) ? $secure->sanitize($_GET["avt"]) : "ALL");
		$code_flight = strtoupper(!empty($_GET["flt"]) ? $secure->sanitize($_GET["flt"]) : "ALL");
		$no_flight = strtoupper(!empty($_GET["no"]) ? $no : "");
		$flight_no = !empty($no_flight) ? $code_flight.$no_flight : "";
		$from_date = $datetime->database_date($from);
		$to_date = $datetime->database_date($to);
		
		$profile = $mysqli->query("select t_profile.company as company, t_profile.branch as branch from t_profile");
		$p = $profile->fetch_assoc();
		
		$header = "<header>
						<table class='header'>
							<thead>
								<tr>
								    <td width='10%'><img src='../../assets/img/aerofood.jpg' width='100px' style='margin:0px 15px;padding:5px 0px;' /></td>
									<td style='padding:0px 10px;font-weight:bold;font-size:14pt;'>FINAL MEAL UPLIFT<br> SUMMARY REPORT</td>
								</tr>
							</thead>
						</table>
						
						<table class='section'>
						  	<thead>
							  	<tr>
									<td style='width:60px;font-weight:bold;'>BRANCH</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td>".$p["branch"]."</td>
									<td style='width:70px;font-weight:bold;'>FROM DATE</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td style='width:50px;text-align:right;'>".$from."</td>
								</tr>
								<tr>
									<td style='font-weight:bold;'>AVT / FLT</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td>".$aviation_flight." / ".$code_flight."".$no_flight."</td>
									<td style='font-weight:bold;'>TO DATE</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td style='text-align:right;'>".$to."</td>
							  	</tr>
						  	</thead>
						</table>
					</header>";
		
		$footer = "<footer>
						<table class='footer'>
							<tr>
								<td colspan='2' style='border-top:1px solid #000000;vertical-align:top;'></td>
							</tr>
							<tr>
							    <td style='text-align:left;'>".$p["company"]."</td>
								<td style='text-align:right;'>AVOS</span></td>
							</tr>
						</table>
					</footer>";
		
		$html .= "<div class='content'></div><table class='table'>
				    <thead>
						<tr>
							<th rowspan='2' style='width:40px;'>NO</th>
							<th rowspan='2' style='width:70px;'>DATE</th>
							<th rowspan='2' style='width:100px;'>FLIGHT</th>
							<th rowspan='2' style='width:100px;'>DEST</th>
							<th rowspan='2' style='width:100px;'>REG</th>
							<th rowspan='2' style='width:80px;'>A/C</th>
							<th rowspan='2' style='width:50px;'>E/Q</th>
							<th colspan='5' style='width:250px;height:28px;'>MEAL UPLIFT</th>
						</tr>
						<tr>
							<th style='width:50px;height:28px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
						</tr>
					</thead><tbody>";
		
		$no = 1;
		$query = $mysqli->query("select * from view_summary_overcost where date between '".$from_date."' and '".$to_date."' and aviation like '%".$aviation."%' and code like '%".$flight."%' and flight like '%".$flight_no."%'");
		$row = $query->num_rows;
		
		if ($row > 0) {
			$group = array();
			$sub_flight = array();
			$sub_meal_uplift_fc = array();
			$sub_meal_uplift_bc = array();
			$sub_meal_uplift_yc = array();
			$sub_meal_uplift_cr = array();
			$sub_meal_uplift_cp = array();
			
			while ($r = $query->fetch_assoc()) {
				$date = $datetime->indonesian_date($r["date"]);
			    $aviation = $r["aviation"];
				
			    if(!isset($group[$aviation])){
			        $group[$aviation] = "";
			        $sub_flight[$aviation] = "0";
			        $sub_meal_uplift_fc[$aviation] = "0";
					$sub_meal_uplift_bc[$aviation] = "0";
					$sub_meal_uplift_yc[$aviation] = "0";
					$sub_meal_uplift_cr[$aviation] = "0";
					$sub_meal_uplift_cp[$aviation] = "0";
			        $no = 1;
			    }
				
			    $group[$aviation] .= "<tr>
										<td>".$no."</td>
										<td>".$date."</td>
										<td>".$r["flight"]."</td>
										<td>".$r["destination"]."</td>
										<td>".$r["register"]."</td>
										<td>".$r["aircraft"]."</td>
										<td>".$r["equipment"]."</td>
										<td>".$r["meal_uplift_fc"]."</td>
										<td>".$r["meal_uplift_bc"]."</td>
										<td>".$r["meal_uplift_yc"]."</td>
										<td>".$r["meal_uplift_cr"]."</td>
										<td>".$r["meal_uplift_cp"]."</td>
									</tr>";
				
				$sub_flight[$aviation] = $no;
				$sub_meal_uplift_fc[$aviation] += $r["meal_uplift_fc"];
				$sub_meal_uplift_bc[$aviation] += $r["meal_uplift_bc"];
				$sub_meal_uplift_yc[$aviation] += $r["meal_uplift_yc"];
				$sub_meal_uplift_cr[$aviation] += $r["meal_uplift_cr"];
				$sub_meal_uplift_cp[$aviation] += $r["meal_uplift_cp"];
				
				$sum_flight = $row;
				$sum_meal_uplift_fc += $r["meal_uplift_fc"];
				$sum_meal_uplift_bc += $r["meal_uplift_bc"];
				$sum_meal_uplift_yc += $r["meal_uplift_yc"];
				$sum_meal_uplift_cr += $r["meal_uplift_cr"];
				$sum_meal_uplift_cp += $r["meal_uplift_cp"];
				
				$no++;
			}
			
			foreach($group as $aviation=>$grouping){
			    $html .= $group[$aviation];
			    $html .= "<tr style='background:#dddddd;'>
			    			<th colspan='2' style='height:28px;'>".$aviation."</th>
			    			<th>".$sub_flight[$aviation]."</th>
							<th colspan='4' style='text-align:right;'>SUB TOTAL&nbsp;&nbsp;</th>
							<th>".$sub_meal_uplift_fc[$aviation]."</th>
							<th>".$sub_meal_uplift_bc[$aviation]."</th>
							<th>".$sub_meal_uplift_yc[$aviation]."</th>
							<th>".$sub_meal_uplift_cr[$aviation]."</th>
							<th>".$sub_meal_uplift_cp[$aviation]."</th>
						</tr>";
			}
			
			$html .= "<tr style='background:#cccccc;'>
						<th colspan='2' style='height:28px;'>ALL</th>
						<th>".$sum_flight."</th>
						<th colspan='4' style='text-align:right;'>GRAND TOTAL&nbsp;&nbsp;</th>
						<th>".$sum_meal_uplift_fc."</th>
						<th>".$sum_meal_uplift_bc."</th>
						<th>".$sum_meal_uplift_yc."</th>
						<th>".$sum_meal_uplift_cr."</th>
						<th>".$sum_meal_uplift_cp."</th>
					</tr>";
		}
		$html .= "</tbody></table>";
		
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
			  	<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
					<head>
						<title>AVOS - MEAL UPLIFT REPORT</title>
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
				    	<link type='image/x-icon' rel='shortcut icon' href='../../assets/img/favicon.ico' media='all' />
				    	<link type='image/ico' rel='icon' href='../../assets/img/favicon.ico' media='all' />
						<style>
							.header { width:100%;margin-bottom:15px;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.header tr td { padding:2px 5px 2px 5px;border:1px solid #000000; }
						 	.section { width:100%;margin-bottom:15px;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.table { width:100%;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt;white-space:nowrap; }
							.table thead tr { background:#cccccc;border:1px solid #000000; }
							.table tbody { background:#ffffff;border:1px solid #000000; }
						 	.table tr th { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center;font-weight:bold; }
							.table tr td { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center; }
							.footer { width:100%;font-weight:bold;font-style:italic;font-size:5pt;color:#999999; }
						</style>
					</head>
					<body>".$header.$html.$footer."</body>
				</html>";
		
		$filename = "AVOS_FINAL_MEAL_UPLIFT_SUMMARY_REPORT_".$aviation_flight."_(".$code_flight."".$no_flight.")_DATE_".$from."_TO_".$to;
		
		if ($export == "word") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-word; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".doc");
		} elseif ($export == "excel") {
		    header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".xls");
		}
	} elseif ($page == "report" and $act == "oversupply") {
		$export = !empty($_GET["export"]) ? $secure->sanitize($_GET["export"]) : "";
		$aviation = $_GET["avt"] != "all" ? strtoupper($secure->sanitize($_GET["avt"])) : "";
		$flight = $_GET["flt"] != "all" ? strtoupper($secure->sanitize($_GET["flt"])) : "";
		$no = $_GET["no"] != "all" ? "-".strtoupper($secure->sanitize($_GET["no"])) : "";
		$from = !empty($_GET["from"]) ? $secure->sanitize($_GET["from"]) : date("d/m/Y");
		$to = !empty($_GET["to"]) ? $secure->sanitize($_GET["to"]) : date("d/m/Y");
		$aviation_flight = strtoupper(!empty($_GET["avt"]) ? $secure->sanitize($_GET["avt"]) : "ALL");
		$code_flight = strtoupper(!empty($_GET["flt"]) ? $secure->sanitize($_GET["flt"]) : "ALL");
		$no_flight = strtoupper(!empty($_GET["no"]) ? $no : "");
		$flight_no = !empty($no_flight) ? $code_flight.$no_flight : "";
		$from_date = $datetime->database_date($from);
		$to_date = $datetime->database_date($to);
		
		$profile = $mysqli->query("select t_profile.company as company, t_profile.branch as branch from t_profile");
		$p = $profile->fetch_assoc();
		
		$header = "<header>
						<table class='header'>
							<thead>
								<tr>
								    <td width='12%'><img src='../../assets/img/aerofood.jpg' width='100px' style='margin:0px 15px;padding:5px 0px;' /></td>
									<td style='padding:0px 10px;font-weight:bold;font-size:14pt;'>OVER SUPPLY<br> SUMMARY REPORT</td>
								</tr>
							</thead>
						</table>
						
						<table class='section'>
						  	<thead>
							  	<tr>
									<td style='width:60px;font-weight:bold;'>BRANCH</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td>".$p["branch"]."</td>
									<td style='width:70px;font-weight:bold;'>FROM DATE</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td style='width:50px;text-align:right;'>".$from."</td>
								</tr>
								<tr>
									<td style='font-weight:bold;'>AVT / FLT</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td>".$aviation_flight." / ".$code_flight."".$no_flight."</td>
									<td style='font-weight:bold;'>TO DATE</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td style='text-align:right;'>".$to."</td>
							  	</tr>
						  	</thead>
						</table>
					</header>";
		
		$footer = "<footer>
						<table class='footer'>
							<tr>
								<td colspan='2' style='border-top:1px solid #000000;vertical-align:top;'></td>
							</tr>
							<tr>
							    <td style='text-align:left;'>".$p["company"]."</td>
								<td style='text-align:right;'>AVOS</span></td>
							</tr>
						</table>
					</footer>";
		
		$html .= "<div class='content'></div><table class='table'>
				    <thead>
						<tr>
							<th rowspan='2' style='width:40px;'>NO</th>
							<th rowspan='2' style='width:70px;'>DATE</th>
							<th rowspan='2' style='width:100px;'>FLIGHT</th>
							<th rowspan='2' style='width:100px;'>DEST</th>
							<th rowspan='2' style='width:100px;'>REG</th>
							<th rowspan='2' style='width:80px;'>A/C</th>
							<th rowspan='2' style='width:50px;'>E/Q</th>
							<th colspan='5' style='width:250px;height:28px;'>MEAL ORDER</th>
							<th colspan='5' style='width:250px;height:28px;'>MEAL UPLIFT</th>
							<th colspan='5' style='width:250px;height:28px;'>OVER SUPPLY</th>
							<th colspan='5' style='width:250px;height:28px;'>PERCENTAGE</th>
						</tr>
						<tr>
							<th style='width:50px;height:28px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
						</tr>
					</thead><tbody>";
					
		$no = 1;
		$query = $mysqli->query("select * from view_summary_overcost where date between '".$from_date."' and '".$to_date."' and aviation like '%".$aviation."%' and code like '%".$flight."%' and flight like '%".$flight_no."%'");
		$row = $query->num_rows;
		
		if ($row > 0) {
			$group = array();
			$sub_flight = array();
			$sub_meal_uplift_fc = array();
			$sub_meal_uplift_bc = array();
			$sub_meal_uplift_yc = array();
			$sub_meal_uplift_cr = array();
			$sub_meal_uplift_cp = array();
			$sub_meal_order_fc = array();
			$sub_meal_order_bc = array();
			$sub_meal_order_yc = array();
			$sub_meal_order_cr = array();
			$sub_meal_order_cp = array();
			$sub_over_supply_fc = array();
			$sub_over_supply_bc = array();
			$sub_over_supply_yc = array();
			$sub_over_supply_cr = array();
			$sub_over_supply_cp = array();
			$sub_percentage_fc = array();
			$sub_percentage_bc = array();
			$sub_percentage_yc = array();
			$sub_percentage_cr = array();
			$sub_percentage_cp = array();
			
			while ($r = $query->fetch_assoc()) {
				$date = $datetime->indonesian_date($r["date"]);
			    $aviation = $r["aviation"];
				
			    if(!isset($group[$aviation])){
			        $group[$aviation] = "";
			        $sub_flight[$aviation] = "0";
					$sub_meal_order_fc[$aviation] = "0";
					$sub_meal_order_bc[$aviation] = "0";
					$sub_meal_order_yc[$aviation] = "0";
					$sub_meal_order_cr[$aviation] = "0";
					$sub_meal_order_cp[$aviation] = "0";
					$sub_meal_uplift_fc[$aviation] = "0";
					$sub_meal_uplift_bc[$aviation] = "0";
					$sub_meal_uplift_yc[$aviation] = "0";
					$sub_meal_uplift_cr[$aviation] = "0";
					$sub_meal_uplift_cp[$aviation] = "0";
					$sub_over_supply_fc[$aviation] = "0";
					$sub_over_supply_bc[$aviation] = "0";
					$sub_over_supply_yc[$aviation] = "0";
					$sub_over_supply_cr[$aviation] = "0";
					$sub_over_supply_cp[$aviation] = "0";
					$sub_percentage_fc[$aviation] = "0";
					$sub_percentage_bc[$aviation] = "0";
					$sub_percentage_yc[$aviation] = "0";
					$sub_percentage_cr[$aviation] = "0";
					$sub_percentage_cp[$aviation] = "0";
			        $no = 1;
			    }
				
			    $group[$aviation] .= "<tr>
										<td>".$no."</td>
										<td>".$date."</td>
										<td>".$r["flight"]."</td>
										<td>".$r["destination"]."</td>
										<td>".$r["register"]."</td>
										<td>".$r["aircraft"]."</td>
										<td>".$r["equipment"]."</td>
										<td>".$r["meal_order_fc"]."</td>
										<td>".$r["meal_order_bc"]."</td>
										<td>".$r["meal_order_yc"]."</td>
										<td>".$r["meal_order_cr"]."</td>
										<td>".$r["meal_order_cp"]."</td>
										<td>".$r["meal_uplift_fc"]."</td>
										<td>".$r["meal_uplift_bc"]."</td>
										<td>".$r["meal_uplift_yc"]."</td>
										<td>".$r["meal_uplift_cr"]."</td>
										<td>".$r["meal_uplift_cp"]."</td>
										<td>".$r["over_supply_fc"]."</td>
										<td>".$r["over_supply_bc"]."</td>
										<td>".$r["over_supply_yc"]."</td>
										<td>".$r["over_supply_cr"]."</td>
										<td>".$r["over_supply_cp"]."</td>
										<td>".number_format(!is_nan(($r["over_supply_fc"] / $r["meal_uplift_fc"]) * 100) && !is_infinite(($r["over_supply_fc"] / $r["meal_uplift_fc"]) * 100) ? ($r["over_supply_fc"] / $r["meal_uplift_fc"]) * 100 : 0, 2)." %</td>
										<td>".number_format(!is_nan(($r["over_supply_bc"] / $r["meal_uplift_bc"]) * 100) && !is_infinite(($r["over_supply_bc"] / $r["meal_uplift_bc"]) * 100) ? ($r["over_supply_bc"] / $r["meal_uplift_bc"]) * 100 : 0, 2)." %</td>
										<td>".number_format(!is_nan(($r["over_supply_yc"] / $r["meal_uplift_yc"]) * 100) && !is_infinite(($r["over_supply_yc"] / $r["meal_uplift_yc"]) * 100) ? ($r["over_supply_yc"] / $r["meal_uplift_yc"]) * 100 : 0, 2)." %</td>
										<td>".number_format(!is_nan(($r["over_supply_cr"] / $r["meal_uplift_cr"]) * 100) && !is_infinite(($r["over_supply_cr"] / $r["meal_uplift_cr"]) * 100) ? ($r["over_supply_cr"] / $r["meal_uplift_cr"]) * 100 : 0, 2)." %</td>
										<td>".number_format(!is_nan(($r["over_supply_cp"] / $r["meal_uplift_cp"]) * 100) && !is_infinite(($r["over_supply_cp"] / $r["meal_uplift_cp"]) * 100) ? ($r["over_supply_cp"] / $r["meal_uplift_cp"]) * 100 : 0, 2)." %</td>
									</tr>";
				
				$sub_flight[$aviation] = $no;
				$sub_meal_order_fc[$aviation] += $r["meal_order_fc"];
				$sub_meal_order_bc[$aviation] += $r["meal_order_bc"];
				$sub_meal_order_yc[$aviation] += $r["meal_order_yc"];
				$sub_meal_order_cr[$aviation] += $r["meal_order_cr"];
				$sub_meal_order_cp[$aviation] += $r["meal_order_cp"];
				$sub_meal_uplift_fc[$aviation] += $r["meal_uplift_fc"];
				$sub_meal_uplift_bc[$aviation] += $r["meal_uplift_bc"];
				$sub_meal_uplift_yc[$aviation] += $r["meal_uplift_yc"];
				$sub_meal_uplift_cr[$aviation] += $r["meal_uplift_cr"];
				$sub_meal_uplift_cp[$aviation] += $r["meal_uplift_cp"];
				$sub_over_supply_fc[$aviation] += $r["over_supply_fc"];
				$sub_over_supply_bc[$aviation] += $r["over_supply_bc"];
				$sub_over_supply_yc[$aviation] += $r["over_supply_yc"];
				$sub_over_supply_cr[$aviation] += $r["over_supply_cr"];
				$sub_over_supply_cp[$aviation] += $r["over_supply_cp"];
				$sub_percentage_fc[$aviation] = number_format(!is_nan(($sub_over_supply_fc[$aviation] / $sub_meal_uplift_fc[$aviation]) * 100) && !is_infinite(($sub_over_supply_fc[$aviation] / $sub_meal_uplift_fc[$aviation]) * 100) ? ($sub_over_supply_fc[$aviation] / $sub_meal_uplift_fc[$aviation]) * 100 : 0, 2);
				$sub_percentage_bc[$aviation] = number_format(!is_nan(($sub_over_supply_bc[$aviation] / $sub_meal_uplift_bc[$aviation]) * 100) && !is_infinite(($sub_over_supply_bc[$aviation] / $sub_meal_uplift_bc[$aviation]) * 100) ? ($sub_over_supply_bc[$aviation] / $sub_meal_uplift_bc[$aviation]) * 100 : 0, 2);
				$sub_percentage_yc[$aviation] = number_format(!is_nan(($sub_over_supply_yc[$aviation] / $sub_meal_uplift_yc[$aviation]) * 100) && !is_infinite(($sub_over_supply_yc[$aviation] / $sub_meal_uplift_yc[$aviation]) * 100) ? ($sub_over_supply_yc[$aviation] / $sub_meal_uplift_yc[$aviation]) * 100 : 0, 2);
				$sub_percentage_cr[$aviation] = number_format(!is_nan(($sub_over_supply_cr[$aviation] / $sub_meal_uplift_cr[$aviation]) * 100) && !is_infinite(($sub_over_supply_cr[$aviation] / $sub_meal_uplift_cr[$aviation]) * 100) ? ($sub_over_supply_cr[$aviation] / $sub_meal_uplift_cr[$aviation]) * 100 : 0, 2);
				$sub_percentage_cp[$aviation] = number_format(!is_nan(($sub_over_supply_cp[$aviation] / $sub_meal_uplift_cp[$aviation]) * 100) && !is_infinite(($sub_over_supply_cp[$aviation] / $sub_meal_uplift_cp[$aviation]) * 100) ? ($sub_over_supply_cp[$aviation] / $sub_meal_uplift_cp[$aviation]) * 100 : 0, 2);
				
				$sum_flight = $row;
				$sum_meal_order_fc += $r["meal_order_fc"];
				$sum_meal_order_bc += $r["meal_order_bc"];
				$sum_meal_order_yc += $r["meal_order_yc"];
				$sum_meal_order_cr += $r["meal_order_cr"];
				$sum_meal_order_cp += $r["meal_order_cp"];
				$sum_meal_uplift_fc += $r["meal_uplift_fc"];
				$sum_meal_uplift_bc += $r["meal_uplift_bc"];
				$sum_meal_uplift_yc += $r["meal_uplift_yc"];
				$sum_meal_uplift_cr += $r["meal_uplift_cr"];
				$sum_meal_uplift_cp += $r["meal_uplift_cp"];
				$sum_over_supply_fc += $r["over_supply_fc"];
				$sum_over_supply_bc += $r["over_supply_bc"];
				$sum_over_supply_yc += $r["over_supply_yc"];
				$sum_over_supply_cr += $r["over_supply_cr"];
				$sum_over_supply_cp += $r["over_supply_cp"];
				$sum_percentage_fc = number_format(!is_nan(($sum_over_supply_fc / $sum_meal_uplift_fc) * 100) && !is_infinite(($sum_over_supply_fc / $sum_meal_uplift_fc) * 100) ? ($sum_over_supply_fc / $sum_meal_uplift_fc) * 100 : 0, 2);
				$sum_percentage_bc = number_format(!is_nan(($sum_over_supply_bc / $sum_meal_uplift_bc) * 100) && !is_infinite(($sum_over_supply_bc / $sum_meal_uplift_bc) * 100) ? ($sum_over_supply_bc / $sum_meal_uplift_bc) * 100 : 0, 2);
				$sum_percentage_yc = number_format(!is_nan(($sum_over_supply_yc / $sum_meal_uplift_yc) * 100) && !is_infinite(($sum_over_supply_yc / $sum_meal_uplift_yc) * 100) ? ($sum_over_supply_yc / $sum_meal_uplift_yc) * 100 : 0, 2);
				$sum_percentage_cr = number_format(!is_nan(($sum_over_supply_cr / $sum_meal_uplift_cr) * 100) && !is_infinite(($sum_over_supply_cr / $sum_meal_uplift_cr) * 100) ? ($sum_over_supply_cr / $sum_meal_uplift_cr) * 100 : 0, 2);
				$sum_percentage_cp = number_format(!is_nan(($sum_over_supply_cp / $sum_meal_uplift_cp) * 100) && !is_infinite(($sum_over_supply_cp / $sum_meal_uplift_cp) * 100) ? ($sum_over_supply_cp / $sum_meal_uplift_cp) * 100 : 0, 2);
				
				$no++;
			}
			
			foreach($group as $aviation=>$grouping){
			    $html .= $group[$aviation];
			    $html .= "<tr style='background:#dddddd;'>
			    			<th colspan='2' style='height:28px;'>".$aviation."</th>
			    			<th>".$sub_flight[$aviation]."</th>
							<th colspan='4' style='text-align:right;'>SUB TOTAL&nbsp;&nbsp;</th>
							<th>".$sub_meal_order_fc[$aviation]."</th>
							<th>".$sub_meal_order_bc[$aviation]."</th>
							<th>".$sub_meal_order_yc[$aviation]."</th>
							<th>".$sub_meal_order_cr[$aviation]."</th>
							<th>".$sub_meal_order_cp[$aviation]."</th>
							<th>".$sub_meal_uplift_fc[$aviation]."</th>
							<th>".$sub_meal_uplift_bc[$aviation]."</th>
							<th>".$sub_meal_uplift_yc[$aviation]."</th>
							<th>".$sub_meal_uplift_cr[$aviation]."</th>
							<th>".$sub_meal_uplift_cp[$aviation]."</th>
							<th>".$sub_over_supply_fc[$aviation]."</th>
							<th>".$sub_over_supply_bc[$aviation]."</th>
							<th>".$sub_over_supply_yc[$aviation]."</th>
							<th>".$sub_over_supply_cr[$aviation]."</th>
							<th>".$sub_over_supply_cp[$aviation]."</th>
							<th>".$sub_percentage_fc[$aviation]." %</th>
							<th>".$sub_percentage_bc[$aviation]." %</th>
							<th>".$sub_percentage_yc[$aviation]." %</th>
							<th>".$sub_percentage_cr[$aviation]." %</th>
							<th>".$sub_percentage_cp[$aviation]." %</th>
						</tr>";
			}
			
			$html .= "<tr style='background:#cccccc;'>
						<th colspan='2' style='height:28px;'>ALL</th>
						<th>".$sum_flight."</th>
						<th colspan='4' style='text-align:right;'>GRAND TOTAL&nbsp;&nbsp;</th>
						<th>".$sum_meal_order_fc."</th>
						<th>".$sum_meal_order_bc."</th>
						<th>".$sum_meal_order_yc."</th>
						<th>".$sum_meal_order_cr."</th>
						<th>".$sum_meal_order_cp."</th>
						<th>".$sum_meal_uplift_fc."</th>
						<th>".$sum_meal_uplift_bc."</th>
						<th>".$sum_meal_uplift_yc."</th>
						<th>".$sum_meal_uplift_cr."</th>
						<th>".$sum_meal_uplift_cp."</th>
						<th>".$sum_over_supply_fc."</th>
						<th>".$sum_over_supply_bc."</th>
						<th>".$sum_over_supply_yc."</th>
						<th>".$sum_over_supply_cr."</th>
						<th>".$sum_over_supply_cp."</th>
						<th>".$sum_percentage_fc." %</th>
						<th>".$sum_percentage_bc." %</th>
						<th>".$sum_percentage_yc." %</th>
						<th>".$sum_percentage_cr." %</th>
						<th>".$sum_percentage_cp." %</th>
					</tr>";
			
			$html .= "<tr style='background:#cccccc;'>
						<th colspan='22' style='height:28px;text-align:right;'>OVER SUPPLY&nbsp;&nbsp;</th>
						<th colspan='3'>".number_format(!is_nan((($sum_over_supply_fc + $sum_over_supply_bc + $sum_over_supply_yc) / ($sum_meal_uplift_fc + $sum_meal_uplift_bc + $sum_meal_uplift_yc)) * 100) && !is_infinite((($sum_over_supply_fc + $sum_over_supply_bc + $sum_over_supply_yc) / ($sum_meal_uplift_fc + $sum_meal_uplift_bc + $sum_meal_uplift_yc)) * 100) ? (($sum_over_supply_fc + $sum_over_supply_bc + $sum_over_supply_yc) / ($sum_meal_uplift_fc + $sum_meal_uplift_bc + $sum_meal_uplift_yc)) * 100 : 0, 2)." %</th>
						<th colspan='2'>".number_format(!is_nan((($sum_over_supply_cr + $sum_over_supply_cp) / ($sum_meal_uplift_cr + $sum_meal_uplift_cp)) * 100) && !is_infinite((($sum_over_supply_cr + $sum_over_supply_cp) / ($sum_meal_uplift_cr + $sum_meal_uplift_cp)) * 100) ? (($sum_over_supply_cr + $sum_over_supply_cp) / ($sum_meal_uplift_cr + $sum_meal_uplift_cp)) * 100 : 0, 2)." %</th>
					</tr>";
		}
		$html .= "</tbody></table>";
		
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
			  	<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
					<head>
						<title>AVOS - OVERCOST REPORT</title>
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
				    	<link type='image/x-icon' rel='shortcut icon' href='../../assets/img/favicon.ico' media='all' />
				    	<link type='image/ico' rel='icon' href='../../assets/img/favicon.ico' media='all' />
						<style>
							.header { width:100%;margin-bottom:15px;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.header tr td { padding:2px 5px 2px 5px;border:1px solid #000000; }
						 	.section { width:100%;margin-bottom:15px;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.table { width:100%;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt;white-space:nowrap; }
							.table thead tr { background:#cccccc;border:1px solid #000000; }
							.table tbody { background:#ffffff;border:1px solid #000000; }
						 	.table tr th { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center;font-weight:bold; }
							.table tr td { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center; }
							.footer { width:100%;font-weight:bold;font-style:italic;font-size:5pt;color:#999999; }
						</style>
					</head>
					<body>".$header.$html.$footer."</body>
				</html>";
		
		$filename = "AVOS_OVER_SUPPLY_SUMMARY_REPORT_".$aviation_flight."_(".$code_flight."".$no_flight.")_DATE_".$from."_TO_".$to;
		
		if ($export == "word") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-word; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".doc");
		} elseif ($export == "excel") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".xls");
		}
	} elseif ($page == "report" and $act == "overproduction") {
		$export = !empty($_GET["export"]) ? $secure->sanitize($_GET["export"]) : "";
		$aviation = $_GET["avt"] != "all" ? strtoupper($secure->sanitize($_GET["avt"])) : "";
		$flight = $_GET["flt"] != "all" ? strtoupper($secure->sanitize($_GET["flt"])) : "";
		$no = $_GET["no"] != "all" ? "-".strtoupper($secure->sanitize($_GET["no"])) : "";
		$from = !empty($_GET["from"]) ? $secure->sanitize($_GET["from"]) : date("d/m/Y");
		$to = !empty($_GET["to"]) ? $secure->sanitize($_GET["to"]) : date("d/m/Y");
		$aviation_flight = strtoupper(!empty($_GET["avt"]) ? $secure->sanitize($_GET["avt"]) : "ALL");
		$code_flight = strtoupper(!empty($_GET["flt"]) ? $secure->sanitize($_GET["flt"]) : "ALL");
		$no_flight = strtoupper(!empty($_GET["no"]) ? $no : "");
		$flight_no = !empty($no_flight) ? $code_flight.$no_flight : "";
		$from_date = $datetime->database_date($from);
		$to_date = $datetime->database_date($to);
		
		$profile = $mysqli->query("select t_profile.company as company, t_profile.branch as branch from t_profile");
		$p = $profile->fetch_assoc();
		
		$header = "<header>
						<table class='header'>
							<thead>
								<tr>
								    <td width='12%'><img src='../../assets/img/aerofood.jpg' width='100px' style='margin:0px 15px;padding:5px 0px;' /></td>
									<td style='padding:0px 10px;font-weight:bold;font-size:14pt;'>OVER PRODUCTION<br> SUMMARY REPORT</td>
								</tr>
							</thead>
						</table>
						
						<table class='section'>
						  	<thead>
							  	<tr>
									<td style='width:60px;font-weight:bold;'>BRANCH</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td>".$p["branch"]."</td>
									<td style='width:70px;font-weight:bold;'>FROM DATE</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td style='width:50px;text-align:right;'>".$from."</td>
								</tr>
								<tr>
									<td style='font-weight:bold;'>AVT / FLT</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td>".$aviation_flight." / ".$code_flight."".$no_flight."</td>
									<td style='font-weight:bold;'>TO DATE</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td style='text-align:right;'>".$to."</td>
							  	</tr>
						  	</thead>
						</table>
					</header>";
		
		$footer = "<footer>
						<table class='footer'>
							<tr>
								<td colspan='2' style='border-top:1px solid #000000;vertical-align:top;'></td>
							</tr>
							<tr>
							    <td style='text-align:left;'>".$p["company"]."</td>
								<td style='text-align:right;'>AVOS</span></td>
							</tr>
						</table>
					</footer>";
		
		$html .= "<div class='content'></div><table class='table'>
				    <thead>
						<tr>
							<th rowspan='2' style='width:40px;'>NO</th>
							<th rowspan='2' style='width:70px;'>DATE</th>
							<th rowspan='2' style='width:100px;'>FLIGHT</th>
							<th rowspan='2' style='width:100px;'>DEST</th>
							<th rowspan='2' style='width:100px;'>REG</th>
							<th rowspan='2' style='width:80px;'>A/C</th>
							<th rowspan='2' style='width:50px;'>E/Q</th>
							<th colspan='5' style='width:250px;height:28px;'>MEAL ORDER</th>
							<th colspan='5' style='width:250px;height:28px;'>PRODUCTION</th>
							<th colspan='5' style='width:250px;height:28px;'>OVER PRODUCTION</th>
							<th colspan='5' style='width:250px;height:28px;'>PERCENTAGE</th>
						</tr>
						<tr>
							<th style='width:50px;height:28px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
						</tr>
					</thead><tbody>";
					
		$no = 1;
		$query = $mysqli->query("select * from view_summary_overcost where date between '".$from_date."' and '".$to_date."' and aviation like '%".$aviation."%' and code like '%".$flight."%' and flight like '%".$flight_no."%'");
		$row = $query->num_rows;
		
		if ($row > 0) {
			$group = array();
			$sub_flight = array();
			$sub_meal_order_fc = array();
			$sub_meal_order_bc = array();
			$sub_meal_order_yc = array();
			$sub_meal_order_cr = array();
			$sub_meal_order_cp = array();
			$sub_production_fc = array();
			$sub_production_bc = array();
			$sub_production_yc = array();
			$sub_production_cr = array();
			$sub_production_cp = array();
			$sub_over_production_fc = array();
			$sub_over_production_bc = array();
			$sub_over_production_yc = array();
			$sub_over_production_cr = array();
			$sub_over_production_cp = array();
			$sub_percentage_fc = array();
			$sub_percentage_bc = array();
			$sub_percentage_yc = array();
			$sub_percentage_cr = array();
			$sub_percentage_cp = array();
			
			while ($r = $query->fetch_assoc()) {
				$date = $datetime->indonesian_date($r["date"]);
			    $aviation = $r["aviation"];
				
			    if(!isset($group[$aviation])){
			        $group[$aviation] = "";
			        $sub_flight[$aviation] = "0";
					$sub_meal_order_fc[$aviation] = "0";
					$sub_meal_order_bc[$aviation] = "0";
					$sub_meal_order_yc[$aviation] = "0";
					$sub_meal_order_cr[$aviation] = "0";
					$sub_meal_order_cp[$aviation] = "0";
					$sub_production_fc[$aviation] = "0";
					$sub_production_bc[$aviation] = "0";
					$sub_production_yc[$aviation] = "0";
					$sub_production_cr[$aviation] = "0";
					$sub_production_cp[$aviation] = "0";
					$sub_over_production_fc[$aviation] = "0";
					$sub_over_production_bc[$aviation] = "0";
					$sub_over_production_yc[$aviation] = "0";
					$sub_over_production_cr[$aviation] = "0";
					$sub_over_production_cp[$aviation] = "0";
					$sub_percentage_fc[$aviation] = "0";
					$sub_percentage_bc[$aviation] = "0";
					$sub_percentage_yc[$aviation] = "0";
					$sub_percentage_cr[$aviation] = "0";
					$sub_percentage_cp[$aviation] = "0";
			        $no = 1;
			    }
				
			    $group[$aviation] .= "<tr>
										<td>".$no."</td>
										<td>".$date."</td>
										<td>".$r["flight"]."</td>
										<td>".$r["destination"]."</td>
										<td>".$r["register"]."</td>
										<td>".$r["aircraft"]."</td>
										<td>".$r["equipment"]."</td>
										<td>".$r["meal_order_fc"]."</td>
										<td>".$r["meal_order_bc"]."</td>
										<td>".$r["meal_order_yc"]."</td>
										<td>".$r["meal_order_cr"]."</td>
										<td>".$r["meal_order_cp"]."</td>
										<td>".$r["production_fc"]."</td>
										<td>".$r["production_bc"]."</td>
										<td>".$r["production_yc"]."</td>
										<td>".$r["production_cr"]."</td>
										<td>".$r["production_cp"]."</td>
										<td>".$r["over_production_fc"]."</td>
										<td>".$r["over_production_bc"]."</td>
										<td>".$r["over_production_yc"]."</td>
										<td>".$r["over_production_cr"]."</td>
										<td>".$r["over_production_cp"]."</td>
										<td>".number_format(($r["over_production_fc"] / $r["production_fc"]) * 100, 2)." %</td>
										<td>".number_format(($r["over_production_bc"] / $r["production_bc"]) * 100, 2)." %</td>
										<td>".number_format(($r["over_production_yc"] / $r["production_yc"]) * 100, 2)." %</td>
										<td>".number_format(($r["over_production_cr"] / $r["production_cr"]) * 100, 2)." %</td>
										<td>".number_format(($r["over_production_cp"] / $r["production_cp"]) * 100, 2)." %</td>
									</tr>";
				
				$sub_flight[$aviation] = $no;
				$sub_meal_order_fc[$aviation] += $r["meal_order_fc"];
				$sub_meal_order_bc[$aviation] += $r["meal_order_bc"];
				$sub_meal_order_yc[$aviation] += $r["meal_order_yc"];
				$sub_meal_order_cr[$aviation] += $r["meal_order_cr"];
				$sub_meal_order_cp[$aviation] += $r["meal_order_cp"];
				$sub_production_fc[$aviation] += $r["production_fc"];
				$sub_production_bc[$aviation] += $r["production_bc"];
				$sub_production_yc[$aviation] += $r["production_yc"];
				$sub_production_cr[$aviation] += $r["production_cr"];
				$sub_production_cp[$aviation] += $r["production_cp"];
				$sub_over_production_fc[$aviation] += $r["over_production_fc"];
				$sub_over_production_bc[$aviation] += $r["over_production_bc"];
				$sub_over_production_yc[$aviation] += $r["over_production_yc"];
				$sub_over_production_cr[$aviation] += $r["over_production_cr"];
				$sub_over_production_cp[$aviation] += $r["over_production_cp"];
				$sub_percentage_fc[$aviation] = number_format(($sub_over_production_fc[$aviation] / $sub_production_fc[$aviation]) * 100, 2);
				$sub_percentage_bc[$aviation] = number_format(($sub_over_production_bc[$aviation] / $sub_production_bc[$aviation]) * 100, 2);
				$sub_percentage_yc[$aviation] = number_format(($sub_over_production_yc[$aviation] / $sub_production_yc[$aviation]) * 100, 2);
				$sub_percentage_cr[$aviation] = number_format(($sub_over_production_cr[$aviation] / $sub_production_cr[$aviation]) * 100, 2);
				$sub_percentage_cp[$aviation] = number_format(($sub_over_production_cp[$aviation] / $sub_production_cp[$aviation]) * 100, 2);
				
				$sum_flight = $row;
				$sum_meal_order_fc += $r["meal_order_fc"];
				$sum_meal_order_bc += $r["meal_order_bc"];
				$sum_meal_order_yc += $r["meal_order_yc"];
				$sum_meal_order_cr += $r["meal_order_cr"];
				$sum_meal_order_cp += $r["meal_order_cp"];
				$sum_production_fc += $r["production_fc"];
				$sum_production_bc += $r["production_bc"];
				$sum_production_yc += $r["production_yc"];
				$sum_production_cr += $r["production_cr"];
				$sum_production_cp += $r["production_cp"];
				$sum_over_production_fc += $r["over_production_fc"];
				$sum_over_production_bc += $r["over_production_bc"];
				$sum_over_production_yc += $r["over_production_yc"];
				$sum_over_production_cr += $r["over_production_cr"];
				$sum_over_production_cp += $r["over_production_cp"];
				$sum_percentage_fc = number_format(($sum_over_production_fc / $sum_production_fc) * 100, 2);
				$sum_percentage_bc = number_format(($sum_over_production_bc / $sum_production_bc) * 100, 2);
				$sum_percentage_yc = number_format(($sum_over_production_yc / $sum_production_yc) * 100, 2);
				$sum_percentage_cr = number_format(($sum_over_production_cr / $sum_production_cr) * 100, 2);
				$sum_percentage_cp = number_format(($sum_over_production_cp / $sum_production_cp) * 100, 2);
				
				$no++;
			}
			
			foreach($group as $aviation=>$grouping){
			    $html .= $group[$aviation];
			    $html .= "<tr style='background:#dddddd;'>
			    			<th colspan='2' style='height:28px;'>".$aviation."</th>
			    			<th>".$sub_flight[$aviation]."</th>
							<th colspan='4' style='text-align:right;'>SUB TOTAL&nbsp;&nbsp;</th>
							<th>".$sub_meal_order_fc[$aviation]."</th>
							<th>".$sub_meal_order_bc[$aviation]."</th>
							<th>".$sub_meal_order_yc[$aviation]."</th>
							<th>".$sub_meal_order_cr[$aviation]."</th>
							<th>".$sub_meal_order_cp[$aviation]."</th>
							<th>".$sub_production_fc[$aviation]."</th>
							<th>".$sub_production_bc[$aviation]."</th>
							<th>".$sub_production_yc[$aviation]."</th>
							<th>".$sub_production_cr[$aviation]."</th>
							<th>".$sub_production_cp[$aviation]."</th>
							<th>".$sub_over_production_fc[$aviation]."</th>
							<th>".$sub_over_production_bc[$aviation]."</th>
							<th>".$sub_over_production_yc[$aviation]."</th>
							<th>".$sub_over_production_cr[$aviation]."</th>
							<th>".$sub_over_production_cp[$aviation]."</th>
							<th>".$sub_percentage_fc[$aviation]." %</th>
							<th>".$sub_percentage_bc[$aviation]." %</th>
							<th>".$sub_percentage_yc[$aviation]." %</th>
							<th>".$sub_percentage_cr[$aviation]." %</th>
							<th>".$sub_percentage_cp[$aviation]." %</th>
						</tr>";
			}
			
			$html .= "<tr style='background:#cccccc;'>
						<th colspan='2' style='height:28px;'>ALL</th>
						<th>".$sum_flight."</th>
						<th colspan='4' style='text-align:right;'>GRAND TOTAL&nbsp;&nbsp;</th>
						<th>".$sum_meal_order_fc."</th>
						<th>".$sum_meal_order_bc."</th>
						<th>".$sum_meal_order_yc."</th>
						<th>".$sum_meal_order_cr."</th>
						<th>".$sum_meal_order_cp."</th>
						<th>".$sum_production_fc."</th>
						<th>".$sum_production_bc."</th>
						<th>".$sum_production_yc."</th>
						<th>".$sum_production_cr."</th>
						<th>".$sum_production_cp."</th>
						<th>".$sum_over_production_fc."</th>
						<th>".$sum_over_production_bc."</th>
						<th>".$sum_over_production_yc."</th>
						<th>".$sum_over_production_cr."</th>
						<th>".$sum_over_production_cp."</th>
						<th>".$sum_percentage_fc." %</th>
						<th>".$sum_percentage_bc." %</th>
						<th>".$sum_percentage_yc." %</th>
						<th>".$sum_percentage_cr." %</th>
						<th>".$sum_percentage_cp." %</th>
					</tr>";
					
			$html .= "<tr style='background:#cccccc;'>
						<th colspan='22' style='height:28px;text-align:right;'>OVER PRODUCTION&nbsp;&nbsp;</th>
						<th colspan='3'>".number_format((($sum_over_production_fc + $sum_over_production_bc + $sum_over_production_yc) / ($sum_production_fc + $sum_production_bc + $sum_production_yc)) * 100, 2)." %</th>
						<th colspan='2'>".number_format((($sum_over_production_cr + $sum_over_production_cp) / ($sum_production_cr + $sum_production_cp)) * 100, 2)." %</th>
					</tr>";
		}
		$html .= "</tbody></table>";
		
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
			  	<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
					<head>
						<title>AVOS - OVERCOST REPORT</title>
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
				    	<link type='image/x-icon' rel='shortcut icon' href='../../assets/img/favicon.ico' media='all' />
				    	<link type='image/ico' rel='icon' href='../../assets/img/favicon.ico' media='all' />
						<style>
							.header { width:100%;margin-bottom:15px;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.header tr td { padding:2px 5px 2px 5px;border:1px solid #000000; }
						 	.section { width:100%;margin-bottom:15px;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.table { width:100%;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt;white-space:nowrap; }
							.table thead tr { background:#cccccc;border:1px solid #000000; }
							.table tbody { background:#ffffff;border:1px solid #000000; }
						 	.table tr th { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center;font-weight:bold; }
							.table tr td { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center; }
							.footer { width:100%;font-weight:bold;font-style:italic;font-size:5pt;color:#999999; }
						</style>
					</head>
					<body>".$header.$html.$footer."</body>
				</html>";
		
		$filename = "AVOS_OVER_PRODUCTION_SUMMARY_REPORT_".$aviation_flight."_(".$code_flight."".$no_flight.")_DATE_".$from."_TO_".$to;
		
		if ($export == "word") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-word; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".doc");
		} elseif ($export == "excel") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".xls");
		}
	} elseif ($page == "report" and $act == "wastedmeal") {
		$export = !empty($_GET["export"]) ? $secure->sanitize($_GET["export"]) : "";
		$aviation = $_GET["avt"] != "all" ? strtoupper($secure->sanitize($_GET["avt"])) : "";
		$flight = $_GET["flt"] != "all" ? strtoupper($secure->sanitize($_GET["flt"])) : "";
		$no = $_GET["no"] != "all" ? "-".strtoupper($secure->sanitize($_GET["no"])) : "";
		$from = !empty($_GET["from"]) ? $secure->sanitize($_GET["from"]) : date("d/m/Y");
		$to = !empty($_GET["to"]) ? $secure->sanitize($_GET["to"]) : date("d/m/Y");
		$aviation_flight = strtoupper(!empty($_GET["avt"]) ? $secure->sanitize($_GET["avt"]) : "ALL");
		$code_flight = strtoupper(!empty($_GET["flt"]) ? $secure->sanitize($_GET["flt"]) : "ALL");
		$no_flight = strtoupper(!empty($_GET["no"]) ? $no : "");
		$flight_no = !empty($no_flight) ? $code_flight.$no_flight : "";
		$from_date = $datetime->database_date($from);
		$to_date = $datetime->database_date($to);
		
		$profile = $mysqli->query("select t_profile.company as company, t_profile.branch as branch from t_profile");
		$p = $profile->fetch_assoc();
		
		$header = "<header>
						<table class='header'>
							<thead>
								<tr>
								    <td width='12%'><img src='../../assets/img/aerofood.jpg' width='100px' style='margin:0px 15px;padding:5px 0px;' /></td>
									<td style='padding:0px 10px;font-weight:bold;font-size:14pt;'>WASTED MEAL<br> SUMMARY REPORT</td>
								</tr>
							</thead>
						</table>
						
						<table class='section'>
						  	<thead>
							  	<tr>
									<td style='width:60px;font-weight:bold;'>BRANCH</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td>".$p["branch"]."</td>
									<td style='width:70px;font-weight:bold;'>FROM DATE</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td style='width:50px;text-align:right;'>".$from."</td>
								</tr>
								<tr>
									<td style='font-weight:bold;'>AVT / FLT</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td>".$aviation_flight." / ".$code_flight."".$no_flight."</td>
									<td style='font-weight:bold;'>TO DATE</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td style='text-align:right;'>".$to."</td>
							  	</tr>
						  	</thead>
						</table>
					</header>";
		
		$footer = "<footer>
						<table class='footer'>
							<tr>
								<td colspan='2' style='border-top:1px solid #000000;vertical-align:top;'></td>
							</tr>
							<tr>
							    <td style='text-align:left;'>".$p["company"]."</td>
								<td style='text-align:right;'>AVOS</span></td>
							</tr>
						</table>
					</footer>";
		
		$html .= "<div class='content'></div><table class='table'>
				    <thead>
						<tr>
							<th rowspan='2' style='width:40px;'>NO</th>
							<th rowspan='2' style='width:70px;'>DATE</th>
							<th rowspan='2' style='width:100px;'>FLIGHT</th>
							<th rowspan='2' style='width:100px;'>DEST</th>
							<th rowspan='2' style='width:100px;'>REG</th>
							<th rowspan='2' style='width:80px;'>A/C</th>
							<th rowspan='2' style='width:50px;'>E/Q</th>
							<th colspan='5' style='width:250px;height:28px;'>MEAL ORDER</th>
							<th colspan='5' style='width:250px;height:28px;'>PAX ON BOARD</th>
							<th colspan='5' style='width:250px;height:28px;'>WASTED MEAL</th>
							<th colspan='5' style='width:250px;height:28px;'>PERCENTAGE</th>
						</tr>
						<tr>
							<th style='width:50px;height:28px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
						</tr>
					</thead><tbody>";
					
		$no = 1;
		$query = $mysqli->query("select * from view_summary_overcost where date between '".$from_date."' and '".$to_date."' and aviation like '%".$aviation."%' and code like '%".$flight."%' and flight like '%".$flight_no."%'");
		$row = $query->num_rows;
		
		if ($row > 0) {
			$group = array();
			$sub_flight = array();
			$sub_meal_order_fc = array();
			$sub_meal_order_bc = array();
			$sub_meal_order_yc = array();
			$sub_meal_order_cr = array();
			$sub_meal_order_cp = array();
			$sub_pax_on_board_fc = array();
			$sub_pax_on_board_bc = array();
			$sub_pax_on_board_yc = array();
			$sub_pax_on_board_cr = array();
			$sub_pax_on_board_cp = array();
			$sub_wasted_meal_fc = array();
			$sub_wasted_meal_bc = array();
			$sub_wasted_meal_yc = array();
			$sub_wasted_meal_cr = array();
			$sub_wasted_meal_cp = array();
			$sub_percentage_fc = array();
			$sub_percentage_bc = array();
			$sub_percentage_yc = array();
			$sub_percentage_cr = array();
			$sub_percentage_cp = array();
			
			while ($r = $query->fetch_assoc()) {
				$date = $datetime->indonesian_date($r["date"]);
			    $aviation = $r["aviation"];
				
			    if(!isset($group[$aviation])){
			        $group[$aviation] = "";
			        $sub_flight[$aviation] = "0";
					$sub_meal_order_fc[$aviation] = "0";
					$sub_meal_order_bc[$aviation] = "0";
					$sub_meal_order_yc[$aviation] = "0";
					$sub_meal_order_cr[$aviation] = "0";
					$sub_meal_order_cp[$aviation] = "0";
					$sub_pax_on_board_fc[$aviation] = "0";
					$sub_pax_on_board_bc[$aviation] = "0";
					$sub_pax_on_board_yc[$aviation] = "0";
					$sub_pax_on_board_cr[$aviation] = "0";
					$sub_pax_on_board_cp[$aviation] = "0";
					$sub_wasted_meal_fc[$aviation] = "0";
					$sub_wasted_meal_bc[$aviation] = "0";
					$sub_wasted_meal_yc[$aviation] = "0";
					$sub_wasted_meal_cr[$aviation] = "0";
					$sub_wasted_meal_cp[$aviation] = "0";
					$sub_percentage_fc[$aviation] = "0";
					$sub_percentage_bc[$aviation] = "0";
					$sub_percentage_yc[$aviation] = "0";
					$sub_percentage_cr[$aviation] = "0";
					$sub_percentage_cp[$aviation] = "0";
			        $no = 1;
			    }
				
			    $group[$aviation] .= "<tr>
										<td>".$no."</td>
										<td>".$date."</td>
										<td>".$r["flight"]."</td>
										<td>".$r["destination"]."</td>
										<td>".$r["register"]."</td>
										<td>".$r["aircraft"]."</td>
										<td>".$r["equipment"]."</td>
										<td>".$r["meal_order_fc"]."</td>
										<td>".$r["meal_order_bc"]."</td>
										<td>".$r["meal_order_yc"]."</td>
										<td>".$r["meal_order_cr"]."</td>
										<td>".$r["meal_order_cp"]."</td>
										<td>".$r["pax_on_board_fc"]."</td>
										<td>".$r["pax_on_board_bc"]."</td>
										<td>".$r["pax_on_board_yc"]."</td>
										<td>".$r["pax_on_board_cr"]."</td>
										<td>".$r["pax_on_board_cp"]."</td>
										<td>".$r["wasted_meal_fc"]."</td>
										<td>".$r["wasted_meal_bc"]."</td>
										<td>".$r["wasted_meal_yc"]."</td>
										<td>".$r["wasted_meal_cr"]."</td>
										<td>".$r["wasted_meal_cp"]."</td>
										<td>".number_format(($r["wasted_meal_fc"] / $r["meal_order_fc"]) * 100, 2)." %</td>
										<td>".number_format(($r["wasted_meal_bc"] / $r["meal_order_bc"]) * 100, 2)." %</td>
										<td>".number_format(($r["wasted_meal_yc"] / $r["meal_order_yc"]) * 100, 2)." %</td>
										<td>".number_format(($r["wasted_meal_cr"] / $r["meal_order_cr"]) * 100, 2)." %</td>
										<td>".number_format(($r["wasted_meal_cp"] / $r["meal_order_cp"]) * 100, 2)." %</td>
									</tr>";
				
				$sub_flight[$aviation] = $no;
				$sub_meal_order_fc[$aviation] += $r["meal_order_fc"];
				$sub_meal_order_bc[$aviation] += $r["meal_order_bc"];
				$sub_meal_order_yc[$aviation] += $r["meal_order_yc"];
				$sub_meal_order_cr[$aviation] += $r["meal_order_cr"];
				$sub_meal_order_cp[$aviation] += $r["meal_order_cp"];
				$sub_pax_on_board_fc[$aviation] += $r["pax_on_board_fc"];
				$sub_pax_on_board_bc[$aviation] += $r["pax_on_board_bc"];
				$sub_pax_on_board_yc[$aviation] += $r["pax_on_board_yc"];
				$sub_pax_on_board_cr[$aviation] += $r["pax_on_board_cr"];
				$sub_pax_on_board_cp[$aviation] += $r["pax_on_board_cp"];
				$sub_wasted_meal_fc[$aviation] += $r["wasted_meal_fc"];
				$sub_wasted_meal_bc[$aviation] += $r["wasted_meal_bc"];
				$sub_wasted_meal_yc[$aviation] += $r["wasted_meal_yc"];
				$sub_wasted_meal_cr[$aviation] += $r["wasted_meal_cr"];
				$sub_wasted_meal_cp[$aviation] += $r["wasted_meal_cp"];
				$sub_percentage_fc[$aviation] = number_format(($sub_wasted_meal_fc[$aviation] / $sub_meal_order_fc[$aviation]) * 100, 2);
				$sub_percentage_bc[$aviation] = number_format(($sub_wasted_meal_bc[$aviation] / $sub_meal_order_bc[$aviation]) * 100, 2);
				$sub_percentage_yc[$aviation] = number_format(($sub_wasted_meal_yc[$aviation] / $sub_meal_order_yc[$aviation]) * 100, 2);
				$sub_percentage_cr[$aviation] = number_format(($sub_wasted_meal_cr[$aviation] / $sub_meal_order_cr[$aviation]) * 100, 2);
				$sub_percentage_cp[$aviation] = number_format(($sub_wasted_meal_cp[$aviation] / $sub_meal_order_cp[$aviation]) * 100, 2);
				
				$sum_flight = $row;
				$sum_meal_order_fc += $r["meal_order_fc"];
				$sum_meal_order_bc += $r["meal_order_bc"];
				$sum_meal_order_yc += $r["meal_order_yc"];
				$sum_meal_order_cr += $r["meal_order_cr"];
				$sum_meal_order_cp += $r["meal_order_cp"];
				$sum_pax_on_board_fc += $r["pax_on_board_fc"];
				$sum_pax_on_board_bc += $r["pax_on_board_bc"];
				$sum_pax_on_board_yc += $r["pax_on_board_yc"];
				$sum_pax_on_board_cr += $r["pax_on_board_cr"];
				$sum_pax_on_board_cp += $r["pax_on_board_cp"];
				$sum_wasted_meal_fc += $r["wasted_meal_fc"];
				$sum_wasted_meal_bc += $r["wasted_meal_bc"];
				$sum_wasted_meal_yc += $r["wasted_meal_yc"];
				$sum_wasted_meal_cr += $r["wasted_meal_cr"];
				$sum_wasted_meal_cp += $r["wasted_meal_cp"];
				$sum_percentage_fc = number_format(($sum_wasted_meal_fc / $sum_meal_order_fc) * 100, 2);
				$sum_percentage_bc = number_format(($sum_wasted_meal_bc / $sum_meal_order_bc) * 100, 2);
				$sum_percentage_yc = number_format(($sum_wasted_meal_yc / $sum_meal_order_yc) * 100, 2);
				$sum_percentage_cr = number_format(($sum_wasted_meal_cr / $sum_meal_order_cr) * 100, 2);
				$sum_percentage_cp = number_format(($sum_wasted_meal_cp / $sum_meal_order_cp) * 100, 2);
				
				$no++;
			}
			
			foreach($group as $aviation=>$grouping){
			    $html .= $group[$aviation];
			    $html .= "<tr style='background:#dddddd;'>
			    			<th colspan='2' style='height:28px;'>".$aviation."</th>
			    			<th>".$sub_flight[$aviation]."</th>
							<th colspan='4' style='text-align:right;'>SUB TOTAL&nbsp;&nbsp;</th>
							<th>".$sub_meal_order_fc[$aviation]."</th>
							<th>".$sub_meal_order_bc[$aviation]."</th>
							<th>".$sub_meal_order_yc[$aviation]."</th>
							<th>".$sub_meal_order_cr[$aviation]."</th>
							<th>".$sub_meal_order_cp[$aviation]."</th>
							<th>".$sub_pax_on_board_fc[$aviation]."</th>
							<th>".$sub_pax_on_board_bc[$aviation]."</th>
							<th>".$sub_pax_on_board_yc[$aviation]."</th>
							<th>".$sub_pax_on_board_cr[$aviation]."</th>
							<th>".$sub_pax_on_board_cp[$aviation]."</th>
							<th>".$sub_wasted_meal_fc[$aviation]."</th>
							<th>".$sub_wasted_meal_bc[$aviation]."</th>
							<th>".$sub_wasted_meal_yc[$aviation]."</th>
							<th>".$sub_wasted_meal_cr[$aviation]."</th>
							<th>".$sub_wasted_meal_cp[$aviation]."</th>
							<th>".$sub_percentage_fc[$aviation]." %</th>
							<th>".$sub_percentage_bc[$aviation]." %</th>
							<th>".$sub_percentage_yc[$aviation]." %</th>
							<th>".$sub_percentage_cr[$aviation]." %</th>
							<th>".$sub_percentage_cp[$aviation]." %</th>
						</tr>";
			}
			
			$html .= "<tr style='background:#cccccc;'>
						<th colspan='2' style='height:28px;'>ALL</th>
						<th>".$sum_flight."</th>
						<th colspan='4' style='text-align:right;'>GRAND TOTAL&nbsp;&nbsp;</th>
						<th>".$sum_meal_order_fc."</th>
						<th>".$sum_meal_order_bc."</th>
						<th>".$sum_meal_order_yc."</th>
						<th>".$sum_meal_order_cr."</th>
						<th>".$sum_meal_order_cp."</th>
						<th>".$sum_pax_on_board_fc."</th>
						<th>".$sum_pax_on_board_bc."</th>
						<th>".$sum_pax_on_board_yc."</th>
						<th>".$sum_pax_on_board_cr."</th>
						<th>".$sum_pax_on_board_cp."</th>
						<th>".$sum_wasted_meal_fc."</th>
						<th>".$sum_wasted_meal_bc."</th>
						<th>".$sum_wasted_meal_yc."</th>
						<th>".$sum_wasted_meal_cr."</th>
						<th>".$sum_wasted_meal_cp."</th>
						<th>".$sum_percentage_fc." %</th>
						<th>".$sum_percentage_bc." %</th>
						<th>".$sum_percentage_yc." %</th>
						<th>".$sum_percentage_cr." %</th>
						<th>".$sum_percentage_cp." %</th>
					</tr>";
					
			$html .= "<tr style='background:#cccccc;'>
						<th colspan='22' style='height:28px;text-align:right;'>WASTED MEAL&nbsp;&nbsp;</th>
						<th colspan='3'>".number_format((($sum_wasted_meal_fc + $sum_wasted_meal_bc + $sum_wasted_meal_yc) / ($sum_meal_order_fc + $sum_meal_order_bc + $sum_meal_order_yc)) * 100, 2)." %</th>
						<th colspan='2'></th>
					</tr>";
		}
		$html .= "</tbody></table>";
		
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
			  	<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
					<head>
						<title>AVOS - OVERCOST REPORT</title>
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
				    	<link type='image/x-icon' rel='shortcut icon' href='../../assets/img/favicon.ico' media='all' />
				    	<link type='image/ico' rel='icon' href='../../assets/img/favicon.ico' media='all' />
						<style>
							.header { width:100%;margin-bottom:15px;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.header tr td { padding:2px 5px 2px 5px;border:1px solid #000000; }
						 	.section { width:100%;margin-bottom:15px;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.table { width:100%;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt;white-space:nowrap; }
							.table thead tr { background:#cccccc;border:1px solid #000000; }
							.table tbody { background:#ffffff;border:1px solid #000000; }
						 	.table tr th { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center;font-weight:bold; }
							.table tr td { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center; }
							.footer { width:100%;font-weight:bold;font-style:italic;font-size:5pt;color:#999999; }
						</style>
					</head>
					<body>".$header.$html.$footer."</body>
				</html>";
		
		$filename = "AVOS_WASTED_MEAL_SUMMARY_REPORT_".$aviation_flight."_(".$code_flight."".$no_flight.")_DATE_".$from."_TO_".$to;
		
		if ($export == "word") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-word; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".doc");
		} elseif ($export == "excel") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".xls");
		}
	} elseif ($page == "report" and $act == "overcost") {
		$export = !empty($_GET["export"]) ? $secure->sanitize($_GET["export"]) : "";
		$aviation = $_GET["avt"] != "all" ? strtoupper($secure->sanitize($_GET["avt"])) : "";
		$flight = $_GET["flt"] != "all" ? strtoupper($secure->sanitize($_GET["flt"])) : "";
		$no = $_GET["no"] != "all" ? "-".strtoupper($secure->sanitize($_GET["no"])) : "";
		$from = !empty($_GET["from"]) ? $secure->sanitize($_GET["from"]) : date("d/m/Y");
		$to = !empty($_GET["to"]) ? $secure->sanitize($_GET["to"]) : date("d/m/Y");
		$aviation_flight = strtoupper(!empty($_GET["avt"]) ? $secure->sanitize($_GET["avt"]) : "ALL");
		$code_flight = strtoupper(!empty($_GET["flt"]) ? $secure->sanitize($_GET["flt"]) : "ALL");
		$no_flight = strtoupper(!empty($_GET["no"]) ? $no : "");
		$flight_no = !empty($no_flight) ? $code_flight.$no_flight : "";
		$from_date = $datetime->database_date($from);
		$to_date = $datetime->database_date($to);
		
		$profile = $mysqli->query("select t_profile.company as company, t_profile.branch as branch from t_profile");
		$p = $profile->fetch_assoc();
		
		$header = "<header>
						<table class='header'>
							<thead>
								<tr>
								    <td width='12%'><img src='../../assets/img/aerofood.jpg' width='100px' style='margin:0px 15px;padding:5px 0px;' /></td>
									<td style='padding:0px 10px;font-weight:bold;font-size:14pt;'>OVERCOST CONTROL<br> SUMMARY REPORT</td>
								</tr>
							</thead>
						</table>
						
						<table class='section'>
						  	<thead>
							  	<tr>
									<td style='width:60px;font-weight:bold;'>BRANCH</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td>".$p["branch"]."</td>
									<td style='width:70px;font-weight:bold;'>FROM DATE</td>
									<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
									<td style='width:50px;text-align:right;'>".$from."</td>
								</tr>
								<tr>
									<td style='font-weight:bold;'>AVT / FLT</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td>".$aviation_flight." / ".$code_flight."".$no_flight."</td>
									<td style='font-weight:bold;'>TO DATE</td>
									<td style='text-align:center;font-weight:bold;'>:</td>
									<td style='text-align:right;'>".$to."</td>
							  	</tr>
						  	</thead>
						</table>
					</header>";
		
		$footer = "<footer>
						<table class='footer'>
							<tr>
								<td colspan='2' style='border-top:1px solid #000000;vertical-align:top;'></td>
							</tr>
							<tr>
							    <td style='text-align:left;'>".$p["company"]."</td>
								<td style='text-align:right;'>AVOS</span></td>
							</tr>
						</table>
					</footer>";
		
		$html .= "<div class='content'></div><table class='table'>
				    <thead>
						<tr>
							<th rowspan='2' style='width:40px;'>NO</th>
							<th rowspan='2' style='width:70px;'>DATE</th>
							<th rowspan='2' style='width:100px;'>FLIGHT</th>
							<th rowspan='2' style='width:100px;'>DEST</th>
							<th rowspan='2' style='width:100px;'>REG</th>
							<th rowspan='2' style='width:80px;'>A/C</th>
							<th rowspan='2' style='width:50px;'>E/Q</th>
							<th colspan='5' style='width:250px;height:28px;'>OVER SUPPLY</th>
							<th colspan='5' style='width:250px;height:28px;'>OVER PRODUCTION</th>
							<th colspan='5' style='width:250px;height:28px;'>WASTED MEAL</th>
						</tr>
						<tr>
							<th style='width:50px;height:28px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
							<th style='width:50px;'>F/C</th>
							<th style='width:50px;'>B/C</th>
							<th style='width:50px;'>Y/C</th>
							<th style='width:50px;'>C/R</th>
							<th style='width:50px;'>C/P</th>
						</tr>
					</thead><tbody>";
		
		$no = 1;
		$query = $mysqli->query("select * from view_summary_overcost where date between '".$from_date."' and '".$to_date."' and aviation like '%".$aviation."%' and code like '%".$flight."%' and flight like '%".$flight_no."%'");
		$row = $query->num_rows;
		
		if ($row > 0) {
			$group = array();
			$sub_flight = array();
			$sub_over_supply_fc = array();
			$sub_over_supply_bc = array();
			$sub_over_supply_yc = array();
			$sub_over_supply_cr = array();
			$sub_over_supply_cp = array();
			$sub_over_production_fc = array();
			$sub_over_production_bc = array();
			$sub_over_production_yc = array();
			$sub_over_production_cr = array();
			$sub_over_production_cp = array();
			$sub_wasted_meal_fc = array();
			$sub_wasted_meal_bc = array();
			$sub_wasted_meal_yc = array();
			$sub_wasted_meal_cr = array();
			$sub_wasted_meal_cp = array();
			
			while ($r = $query->fetch_assoc()) {
				$date = $datetime->indonesian_date($r["date"]);
			    $aviation = $r["aviation"];
				
			    if(!isset($group[$aviation])){
			        $group[$aviation] = "";
			        $sub_flight[$aviation] = "0";
					$sub_over_supply_fc[$aviation] = "0";
					$sub_over_supply_bc[$aviation] = "0";
					$sub_over_supply_yc[$aviation] = "0";
					$sub_over_supply_cr[$aviation] = "0";
					$sub_over_supply_cp[$aviation] = "0";
					$sub_over_production_fc[$aviation] = "0";
					$sub_over_production_bc[$aviation] = "0";
					$sub_over_production_yc[$aviation] = "0";
					$sub_over_production_cr[$aviation] = "0";
					$sub_over_production_cp[$aviation] = "0";
					$sub_wasted_meal_fc[$aviation] = "0";
					$sub_wasted_meal_bc[$aviation] = "0";
					$sub_wasted_meal_yc[$aviation] = "0";
					$sub_wasted_meal_cr[$aviation] = "0";
					$sub_wasted_meal_cp[$aviation] = "0";
			        $no = 1;
			    }
				
			    $group[$aviation] .= "<tr>
										<td>".$no."</td>
										<td>".$date."</td>
										<td>".$r["flight"]."</td>
										<td>".$r["destination"]."</td>
										<td>".$r["register"]."</td>
										<td>".$r["aircraft"]."</td>
										<td>".$r["equipment"]."</td>
										<td>".$r["over_supply_fc"]."</td>
										<td>".$r["over_supply_bc"]."</td>
										<td>".$r["over_supply_yc"]."</td>
										<td>".$r["over_supply_cr"]."</td>
										<td>".$r["over_supply_cp"]."</td>
										<td>".$r["over_production_fc"]."</td>
										<td>".$r["over_production_bc"]."</td>
										<td>".$r["over_production_yc"]."</td>
										<td>".$r["over_production_cr"]."</td>
										<td>".$r["over_production_cp"]."</td>
										<td>".$r["wasted_meal_fc"]."</td>
										<td>".$r["wasted_meal_bc"]."</td>
										<td>".$r["wasted_meal_yc"]."</td>
										<td>".$r["wasted_meal_cr"]."</td>
										<td>".$r["wasted_meal_cp"]."</td>
									</tr>";
				
				$sub_flight[$aviation] = $no;
				$sub_over_supply_fc[$aviation] += $r["over_supply_fc"];
				$sub_over_supply_bc[$aviation] += $r["over_supply_bc"];
				$sub_over_supply_yc[$aviation] += $r["over_supply_yc"];
				$sub_over_supply_cr[$aviation] += $r["over_supply_cr"];
				$sub_over_supply_cp[$aviation] += $r["over_supply_cp"];
				$sub_over_production_fc[$aviation] += $r["over_production_fc"];
				$sub_over_production_bc[$aviation] += $r["over_production_bc"];
				$sub_over_production_yc[$aviation] += $r["over_production_yc"];
				$sub_over_production_cr[$aviation] += $r["over_production_cr"];
				$sub_over_production_cp[$aviation] += $r["over_production_cp"];
				$sub_wasted_meal_fc[$aviation] += $r["wasted_meal_fc"];
				$sub_wasted_meal_bc[$aviation] += $r["wasted_meal_bc"];
				$sub_wasted_meal_yc[$aviation] += $r["wasted_meal_yc"];
				$sub_wasted_meal_cr[$aviation] += $r["wasted_meal_cr"];
				$sub_wasted_meal_cp[$aviation] += $r["wasted_meal_cp"];
				
				$sum_flight = $row;
				$sum_over_supply_fc += $r["over_supply_fc"];
				$sum_over_supply_bc += $r["over_supply_bc"];
				$sum_over_supply_yc += $r["over_supply_yc"];
				$sum_over_supply_cr += $r["over_supply_cr"];
				$sum_over_supply_cp += $r["over_supply_cp"];
				$sum_over_production_fc += $r["over_production_fc"];
				$sum_over_production_bc += $r["over_production_bc"];
				$sum_over_production_yc += $r["over_production_yc"];
				$sum_over_production_cr += $r["over_production_cr"];
				$sum_over_production_cp += $r["over_production_cp"];
				$sum_wasted_meal_fc += $r["wasted_meal_fc"];
				$sum_wasted_meal_bc += $r["wasted_meal_bc"];
				$sum_wasted_meal_yc += $r["wasted_meal_yc"];
				$sum_wasted_meal_cr += $r["wasted_meal_cr"];
				$sum_wasted_meal_cp += $r["wasted_meal_cp"];
				
				$no++;
			}
			
			foreach($group as $aviation=>$grouping){
			    $html .= $group[$aviation];
			    $html .= "<tr style='background:#dddddd;'>
			    			<th colspan='2' style='height:28px;'>".$aviation."</th>
			    			<th>".$sub_flight[$aviation]."</th>
							<th colspan='4' style='text-align:right;'>SUB TOTAL&nbsp;&nbsp;</th>
							<th>".$sub_over_supply_fc[$aviation]."</th>
							<th>".$sub_over_supply_bc[$aviation]."</th>
							<th>".$sub_over_supply_yc[$aviation]."</th>
							<th>".$sub_over_supply_cr[$aviation]."</th>
							<th>".$sub_over_supply_cp[$aviation]."</th>
							<th>".$sub_over_production_fc[$aviation]."</th>
							<th>".$sub_over_production_bc[$aviation]."</th>
							<th>".$sub_over_production_yc[$aviation]."</th>
							<th>".$sub_over_production_cr[$aviation]."</th>
							<th>".$sub_over_production_cp[$aviation]."</th>
							<th>".$sub_wasted_meal_fc[$aviation]."</th>
							<th>".$sub_wasted_meal_bc[$aviation]."</th>
							<th>".$sub_wasted_meal_yc[$aviation]."</th>
							<th>".$sub_wasted_meal_cr[$aviation]."</th>
							<th>".$sub_wasted_meal_cp[$aviation]."</th>
						</tr>";
			}
			
			$html .= "<tr style='background:#cccccc;'>
						<th colspan='2' style='height:28px;'>ALL</th>
						<th>".$sum_flight."</th>
						<th colspan='4' style='text-align:right;'>GRAND TOTAL&nbsp;&nbsp;</th>
						<th>".$sum_over_supply_fc."</th>
						<th>".$sum_over_supply_bc."</th>
						<th>".$sum_over_supply_yc."</th>
						<th>".$sum_over_supply_cr."</th>
						<th>".$sum_over_supply_cp."</th>
						<th>".$sum_over_production_fc."</th>
						<th>".$sum_over_production_bc."</th>
						<th>".$sum_over_production_yc."</th>
						<th>".$sum_over_production_cr."</th>
						<th>".$sum_over_production_cp."</th>
						<th>".$sum_wasted_meal_fc."</th>
						<th>".$sum_wasted_meal_bc."</th>
						<th>".$sum_wasted_meal_yc."</th>
						<th>".$sum_wasted_meal_cr."</th>
						<th>".$sum_wasted_meal_cp."</th>
					</tr>";
		}
		$html .= "</tbody></table>";
		
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
			  	<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
					<head>
						<title>AVOS - OVERCOST REPORT</title>
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
				    	<link type='image/x-icon' rel='shortcut icon' href='../../assets/img/favicon.ico' media='all' />
				    	<link type='image/ico' rel='icon' href='../../assets/img/favicon.ico' media='all' />
						<style>
							.header { width:100%;margin-bottom:15px;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.header tr td { padding:2px 5px 2px 5px;border:1px solid #000000; }
						 	.section { width:100%;margin-bottom:15px;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.table { width:100%;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt;white-space:nowrap; }
							.table thead tr { background:#cccccc;border:1px solid #000000; }
							.table tbody { background:#ffffff;border:1px solid #000000; }
						 	.table tr th { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center;font-weight:bold; }
							.table tr td { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center; }
							.footer { width:100%;font-weight:bold;font-style:italic;font-size:5pt;color:#999999; }
						</style>
					</head>
					<body>".$header.$html.$footer."</body>
				</html>";
		
		$filename = "AVOS_OVERCOST_SUMMARY_REPORT_".$aviation_flight."_(".$code_flight."".$no_flight.")_DATE_".$from."_TO_".$to;
		
		if ($export == "word") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-word; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".doc");
		} elseif ($export == "excel") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".xls");
		}
	} elseif ($page == "report" and $act == "loadfactor") {
		$export = !empty($_GET["export"]) ? $secure->sanitize($_GET["export"]) : "";
		$aviation = $_GET["avt"] != "all" ? strtoupper($secure->sanitize($_GET["avt"])) : "";
		$flight = $_GET["flt"] != "all" ? strtoupper($secure->sanitize($_GET["flt"])) : "";
		$no = $_GET["no"] != "all" ? "-".strtoupper($secure->sanitize($_GET["no"])) : "";
		$from = !empty($_GET["from"]) ? $secure->sanitize($_GET["from"]) : date("d/m/Y");
		$to = !empty($_GET["to"]) ? $secure->sanitize($_GET["to"]) : date("d/m/Y");
		$aviation_flight = strtoupper(!empty($_GET["avt"]) ? $secure->sanitize($_GET["avt"]) : "ALL");
		$code_flight = strtoupper(!empty($_GET["flt"]) ? $secure->sanitize($_GET["flt"]) : "ALL");
		$no_flight = strtoupper(!empty($_GET["no"]) ? $no : "");
		$flight_no = !empty($no_flight) ? $code_flight.$no_flight : "";
		$from_date = $datetime->database_date($from);
		$to_date = $datetime->database_date($to);
		
		$profile = $mysqli->query("select t_profile.company as company, t_profile.branch as branch from t_profile");
		$p = $profile->fetch_assoc();
		
		$header = "<table class='header'>
						<tr>
						    <td width='10%'><img src='../../assets/img/aerofood.jpg' width='100px' style='margin:0px 15px;padding:5px 0px;' /></td>
							<td style='padding:0px 10px;font-weight:bold;font-size:14pt;'>LOAD FACTOR<br> SUMMARY REPORT</td>
						</tr>
					 </table>
					 
					 <table class='section'>
					  	<thead>
						  	<tr>
								<td style='width:60px;font-weight:bold;'>BRANCH</td>
								<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
								<td>".$p["branch"]."</td>
								<td style='width:70px;font-weight:bold;'>FROM DATE</td>
								<td style='width:20px;text-align:center;font-weight:bold;'>:</td>
								<td style='width:50px;text-align:right;'>".$from."</td>
							</tr>
							<tr>
								<td style='font-weight:bold;'>AVT / FLT</td>
								<td style='text-align:center;font-weight:bold;'>:</td>
								<td>".$aviation_flight." / ".$code_flight."".$no_flight."</td>
								<td style='font-weight:bold;'>TO DATE</td>
								<td style='text-align:center;font-weight:bold;'>:</td>
								<td style='text-align:right;'>".$to."</td>
						  	</tr>
					  	</thead>
					</table>";
		
		$footer = "<table class='footer'>
						<tr>
							<td colspan='2' style='border-top:1px solid #000000;vertical-align:top;'></td>
						</tr>
						<tr>
						    <td style='text-align:left;'>".$p["company"]."</td>
							<td style='text-align:right;'>AVOS</td>
						</tr>
					</table>";
		
		$html .= "<table class='table'>
				    <thead>
						<tr>
							<th rowspan='2' style='width:40px;'>NO</th>
							<th rowspan='2' style='width:70px;'>DATE</th>
							<th rowspan='2' style='width:100px;'>FLIGHT</th>
							<th rowspan='2' style='width:100px;'>DEST</th>
							<th rowspan='2' style='width:100px;'>REG</th>
							<th rowspan='2' style='width:80px;'>A/C</th>
							<th rowspan='2' style='width:50px;'>E/Q</th>
							<th colspan='6' style='width:480px;height:28px;'>PAX ON BOARD</th>
							<th colspan='6' style='width:480px;height:28px;'>CONFIGURATION</th>
							<th colspan='6' style='width:480px;height:28px;'>LOAD FACTOR</th>
						</tr>
						<tr>
							<th style='width:80px;height:28px;'>F/C</th>
							<th style='width:80px;'>B/C</th>
							<th style='width:80px;'>Y/C</th>
							<th style='width:80px;'>C/R</th>
							<th style='width:80px;'>C/P</th>
							<th style='width:80px;'>TOTAL</th>
							<th style='width:80px;'>F/C</th>
							<th style='width:80px;'>B/C</th>
							<th style='width:80px;'>Y/C</th>
							<th style='width:80px;'>C/R</th>
							<th style='width:80px;'>C/P</th>
							<th style='width:80px;'>TOTAL</th>
							<th style='width:80px;'>F/C</th>
							<th style='width:80px;'>B/C</th>
							<th style='width:80px;'>Y/C</th>
							<th style='width:80px;'>C/R</th>
							<th style='width:80px;'>C/P</th>
							<th style='width:80px;'>TOTAL</th>
						</tr>
					</thead><tbody>";
		
		$no = 1;
		$query = $mysqli->query("select * from view_summary_load_factor where date between '".$from_date."' and '".$to_date."' and aviation like '%".$aviation."%' and code like '%".$flight."%' and flight like '%".$flight_no."%'");
		$row = $query->num_rows;
		
		if ($row > 0) {
			$group = array();
			$sub_pax_on_board_fc = array();
			$sub_pax_on_board_bc = array();
			$sub_pax_on_board_yc = array();
			$sub_pax_on_board_cr = array();
			$sub_pax_on_board_cp = array();
			$sub_total_pax_on_board = array();
			$sub_config_fc = array();
			$sub_config_bc = array();
			$sub_config_yc = array();
			$sub_config_cr = array();
			$sub_config_cp = array();
			$sub_config_board = array();
			$sub_load_factor_fc = array();
			$sub_load_factor_bc = array();
			$sub_load_factor_yc = array();
			$sub_load_factor_cr = array();
			$sub_load_factor_cp = array();
			$sub_total_load_factor = array();
			
			while ($r = $query->fetch_assoc()) {
				$date = $datetime->indonesian_date($r["date"]);
			    $aviation = $r["aviation"];
				
			    if(!isset($group[$aviation])){
			        $group[$aviation] = "";
			        $sub_flight[$aviation] = "0";
			        $sub_pax_on_board_fc[$aviation] = "0";
			        $sub_pax_on_board_bc[$aviation] = "0";
					$sub_pax_on_board_yc[$aviation] = "0";
					$sub_pax_on_board_cr[$aviation] = "0";
					$sub_pax_on_board_cp[$aviation] = "0";
					$sub_total_pax_on_board[$aviation] = "0";
					$sub_config_fc[$aviation] = "0";
					$sub_config_bc[$aviation] = "0";
					$sub_config_yc[$aviation] = "0";
					$sub_config_cr[$aviation] = "0";
					$sub_config_cp[$aviation] = "0";
					$sub_total_config[$aviation] = "0";
					$sub_load_factor_fc[$aviation] = "0";
					$sub_load_factor_bc[$aviation] = "0";
					$sub_load_factor_yc[$aviation] = "0";
					$sub_load_factor_cr[$aviation] = "0";
					$sub_load_factor_cp[$aviation] = "0";
					$sub_total_load_factor[$aviation] = "0";
			        $no = 1;
			    }
				
			    $group[$aviation] .= "<tr>
										<td>".$no."</td>
										<td>".$date."</td>
										<td>".$r["flight"]."</td>
										<td>".$r["destination"]."</td>
										<td>".$r["register"]."</td>
										<td>".$r["aircraft"]."</td>
										<td>".$r["equipment"]."</td>
										<td>".$r["pax_on_board_fc"]."</td>
										<td>".$r["pax_on_board_bc"]."</td>
										<td>".$r["pax_on_board_yc"]."</td>
										<td>".$r["pax_on_board_cr"]."</td>
										<td>".$r["pax_on_board_cp"]."</td>
										<td>".$r["total_pax_on_board"]."</td>
										<td>".$r["config_fc"]."</td>
										<td>".$r["config_bc"]."</td>
										<td>".$r["config_yc"]."</td>
										<td>".$r["config_cr"]."</td>
										<td>".$r["config_cp"]."</td>
										<td>".$r["total_config"]."</td>
										<td>".$r["load_factor_fc"]." %</td>
										<td>".$r["load_factor_bc"]." %</td>
										<td>".$r["load_factor_yc"]." %</td>
										<td>".$r["load_factor_cr"]." %</td>
										<td>".$r["load_factor_cp"]." %</td>
										<td>".$r["total_load_factor"]." %</td>
									</tr>";
				
				$sub_flight[$aviation] = $no;
				$sub_pax_on_board_fc[$aviation] += $r["pax_on_board_fc"];
				$sub_pax_on_board_bc[$aviation] += $r["pax_on_board_bc"];
				$sub_pax_on_board_yc[$aviation] += $r["pax_on_board_yc"];
				$sub_pax_on_board_cr[$aviation] += $r["pax_on_board_cr"];
				$sub_pax_on_board_cp[$aviation] += $r["pax_on_board_cp"];
				$sub_total_pax_on_board[$aviation] += $r["total_pax_on_board"];
				$sub_config_fc[$aviation] += $r["config_fc"];
				$sub_config_bc[$aviation] += $r["config_bc"];
				$sub_config_yc[$aviation] += $r["config_yc"];
				$sub_config_cr[$aviation] += $r["config_cr"];
				$sub_config_cp[$aviation] += $r["config_cp"];
				$sub_total_config[$aviation] += $r["total_config"];
				$sub_load_factor_fc[$aviation] = number_format(($sub_pax_on_board_fc[$aviation] / $sub_config_fc[$aviation]) * 100, 2);
				$sub_load_factor_bc[$aviation] = number_format(($sub_pax_on_board_bc[$aviation] / $sub_config_bc[$aviation]) * 100, 2);
				$sub_load_factor_yc[$aviation] = number_format(($sub_pax_on_board_yc[$aviation] / $sub_config_yc[$aviation]) * 100, 2);
				$sub_load_factor_cr[$aviation] = number_format(($sub_pax_on_board_cr[$aviation] / $sub_config_cr[$aviation]) * 100, 2);
				$sub_load_factor_cp[$aviation] = number_format(($sub_pax_on_board_cp[$aviation] / $sub_config_cp[$aviation]) * 100, 2);
				$sub_total_load_factor[$aviation] = number_format(($sub_total_pax_on_board[$aviation] / $sub_total_config[$aviation]) * 100, 2);
				
				$sum_flight = $row;
				$sum_pax_on_board_fc += $r["pax_on_board_fc"];
				$sum_pax_on_board_bc += $r["pax_on_board_bc"];
				$sum_pax_on_board_yc += $r["pax_on_board_yc"];
				$sum_pax_on_board_cr += $r["pax_on_board_cr"];
				$sum_pax_on_board_cp += $r["pax_on_board_cp"];
				$sum_total_pax_on_board += $r["total_pax_on_board"];
				$sum_config_fc += $r["config_fc"];
				$sum_config_bc += $r["config_bc"];
				$sum_config_yc += $r["config_yc"];
				$sum_config_cr += $r["config_cr"];
				$sum_config_cp += $r["config_cp"];
				$sum_total_config += $r["total_config"];
				$sum_load_factor_fc = number_format(($sum_pax_on_board_fc / $sum_config_fc) * 100, 2);
				$sum_load_factor_bc = number_format(($sum_pax_on_board_bc / $sum_config_bc) * 100, 2);
				$sum_load_factor_yc = number_format(($sum_pax_on_board_yc / $sum_config_yc) * 100, 2);
				$sum_load_factor_cr = number_format(($sum_pax_on_board_cr / $sum_config_cr) * 100, 2);
				$sum_load_factor_cp = number_format(($sum_pax_on_board_cp / $sum_config_cp) * 100, 2);
				$sum_total_load_factor = number_format(($sum_total_pax_on_board / $sum_total_config) * 100, 2);
				
				$no++;
			}
			
			foreach($group as $aviation=>$grouping){
			    $html .= $group[$aviation];
			    $html .= "<tr style='background:#dddddd;'>
			    			<th colspan='2' style='height:28px;'>".$aviation."</th>
			    			<th>".$sub_flight[$aviation]."</th>
							<th colspan='4' style='text-align:right;'>SUB TOTAL&nbsp;&nbsp;</th>
							<th>".$sub_pax_on_board_fc[$aviation]."</th>
							<th>".$sub_pax_on_board_bc[$aviation]."</th>
							<th>".$sub_pax_on_board_yc[$aviation]."</th>
							<th>".$sub_pax_on_board_cr[$aviation]."</th>
							<th>".$sub_pax_on_board_cp[$aviation]."</th>
							<th>".$sub_total_pax_on_board[$aviation]."</th>
							<th>".$sub_config_fc[$aviation]."</th>
							<th>".$sub_config_bc[$aviation]."</th>
							<th>".$sub_config_yc[$aviation]."</th>
							<th>".$sub_config_cr[$aviation]."</th>
							<th>".$sub_config_cp[$aviation]."</th>
							<th>".$sub_total_config[$aviation]."</th>
							<th>".$sub_load_factor_fc[$aviation]." %</th>
							<th>".$sub_load_factor_bc[$aviation]." %</th>
							<th>".$sub_load_factor_yc[$aviation]." %</th>
							<th>".$sub_load_factor_cr[$aviation]." %</th>
							<th>".$sub_load_factor_cp[$aviation]." %</th>
							<th>".$sub_total_load_factor[$aviation]." %</th>
						</tr>";
			}
			
			$html .= "<tr style='background:#cccccc;'>
						<th colspan='2' style='height:28px;'>ALL</th>
						<th>".$sum_flight."</th>
						<th colspan='4' style='text-align:right;'>GRAND TOTAL&nbsp;&nbsp;</th>
						<th>".$sum_pax_on_board_fc."</th>
						<th>".$sum_pax_on_board_bc."</th>
						<th>".$sum_pax_on_board_yc."</th>
						<th>".$sum_pax_on_board_cr."</th>
						<th>".$sum_pax_on_board_cp."</th>
						<th>".$sum_total_pax_on_board."</th>
						<th>".$sum_config_fc."</th>
						<th>".$sum_config_bc."</th>
						<th>".$sum_config_yc."</th>
						<th>".$sum_config_cr."</th>
						<th>".$sum_config_cp."</th>
						<th>".$sum_total_config."</th>
						<th>".$sum_load_factor_fc." %</th>
						<th>".$sum_load_factor_bc." %</th>
						<th>".$sum_load_factor_yc." %</th>
						<th>".$sum_load_factor_cr." %</th>
						<th>".$sum_load_factor_cp." %</th>
						<th>".$sum_total_load_factor." %</th>
					</tr>";
		}
		$html .= "</tbody></table>";
		
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
			  	<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
					<head>
						<title>AVOS - LOAD FACTOR REPORT</title>
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
				    	<link type='image/x-icon' rel='shortcut icon' href='../../assets/img/favicon.ico' media='all' />
				    	<link type='image/ico' rel='icon' href='../../assets/img/favicon.ico' media='all' />
						<style>
							.header { width:100%;margin-bottom:15px;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.header tr td { padding:2px 5px 2px 5px;border:1px solid #000000; }
						 	.section { width:100%;margin-bottom:15px;border-collapse:collapse;border-spacing:0;font-size:8pt; }
							.table { width:100%;border:1px solid #000000;border-collapse:collapse;border-spacing:0;font-size:8pt;white-space:nowrap; }
							.table thead tr { background:#cccccc;border:1px solid #000000; }
							.table tbody { background:#ffffff;border:1px solid #000000; }
						 	.table tr th { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center;font-weight:bold; }
							.table tr td { padding:2px;border:1px solid #000000;vertical-align:middle;text-align:center; }
							.footer { width:100%;font-weight:bold;font-style:italic;font-size:5pt;color:#999999; }
						</style>
					</head>
					<body>".$header.$html.$footer."</body>
				</html>";
		
		$filename = "AVOS_LOAD_FACTOR_SUMMARY_REPORT_".$aviation_flight."_(".$code_flight."".$no_flight.")_DATE_".$from."_TO_".$to;
		
		if ($export == "word") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-word; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".doc");
		} elseif ($export == "excel") {
			header("Pragma: public");
		    header("Expires: 0");
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
		    header("Content-Type: application/force-download");
		    header("Content-Type: application/octet-stream");
		    header("Content-Type: application/download");
		    header("Content-Disposition: attachment; filename=".$filename.".xls");
		}
	}
}
?>