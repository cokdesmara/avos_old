<?php
include "../../includes/configuration.php";

if (empty($_SESSION["user_session"])) {
	header("location:../../index.php"); 
} else {
	include "../../includes/connection.php";
	include "../../includes/secure.php";
	include "../../includes/uri.php";
	include "../../includes/datetime.php";
	
	$page = $_GET["page"];
	$act = $_GET["act"];
	
	if ($page == "mealorderpob" and $act == "list") {
		$primary = "view_meal_order_pob.date";
		
		$field = array (
			$primary,
		    "view_meal_order_pob.date",
		    "view_meal_order_pob.date",
		    "view_meal_order_pob.total_flight",
		    "view_meal_order_pob.total_mo_fc",
		    "view_meal_order_pob.total_mo_bc",
		    "view_meal_order_pob.total_mo_yc",
		    "view_meal_order_pob.total_mo_cr",
		    "view_meal_order_pob.total_mo_cp",
		    "view_meal_order_pob.total_pob_fc",
		    "view_meal_order_pob.total_pob_bc",
		    "view_meal_order_pob.total_pob_yc",
		    "view_meal_order_pob.total_pob_cr",
		    "view_meal_order_pob.total_pob_cp",
		    "view_meal_order_pob.total_spml_bbml",
		    "view_meal_order_pob.total_spml_ksml",
		    "view_meal_order_pob.total_spml_hcake",
		    "view_meal_order_pob.total_config_fc",
		    "view_meal_order_pob.total_config_bc",
		    "view_meal_order_pob.total_config_yc",
		    "view_meal_order_pob.total_config_cr",
		    "view_meal_order_pob.total_config_cp"
		);
		
		$table = "view_meal_order_pob";
		$join = "";
		
		$where = "";
		if (isset($_GET["sSearch"]) and $_GET["sSearch"] != "") {
		    $where = "where ";
		    foreach (array_slice($field, 2) as $column) {
		        $where .= $column." like '%".strtoupper($secure->sanitize($_GET["sSearch"]))."%' or ";
		    }
		    $where = substr($where, 0, -3);
		}
		
		$order = "";
		if (isset($_GET["iSortCol_0"])) {
		    $order = "order by ";
		    for ($i = 0; $i < intval($secure->sanitize($_GET["iSortingCols"])); $i++) {
		        $order .= $field[$_GET["iSortCol_".$i]]." ".$secure->sanitize($_GET["sSortDir_".$i]).", ";
		    }
		    $order = substr_replace($order, "", -2);
		}
		
		$limit = "";
		if (isset($_GET["iDisplayStart"]) and $_GET["iDisplayLength"] != "-1") {
		    $limit = "limit ".intval($secure->sanitize($_GET["iDisplayStart"])).", ".intval($secure->sanitize($_GET["iDisplayLength"]));
		}
		
		$query = $mysqli->query("select sql_calc_found_rows ".implode(", ", $field)." from ".$table." ".$join." ".$where." ".$order." ".$limit);
		
		$filter = $mysqli->query("select found_rows()");
		$filter_row = $filter->fetch_row();
		
		$total = $mysqli->query("select count(".$primary.") from ".$table);
		$total_row = $total->fetch_row();
		
		$response = array(
	        "sEcho" => intval($_GET["sEcho"]),
	        "iTotalRecords" => intval($total_row["0"]),
	        "iTotalDisplayRecords" => intval($filter_row["0"]),
	        "aaData" => array()
	    );
		
		$data = array();
		$index = $_GET["iDisplayStart"]+1;
		while ($row = $query->fetch_row()) {
			$data["0"] = $index;
			$data["1"] = $row["1"];
			$data["2"] = $datetime->indonesian_date($row["2"]);
			$data["3"] = number_format($row["3"], 0, ",", ".");
			$data["4"] = number_format($row["4"], 0, ",", ".");
			$data["5"] = number_format($row["5"], 0, ",", ".");
			$data["6"] = number_format($row["6"], 0, ",", ".");
			$data["7"] = number_format($row["7"], 0, ",", ".");
			$data["8"] = number_format($row["8"], 0, ",", ".");
			$data["9"] = number_format($row["9"], 0, ",", ".");
			$data["10"] = number_format($row["10"], 0, ",", ".");
			$data["11"] = number_format($row["11"], 0, ",", ".");
			$data["12"] = number_format($row["12"], 0, ",", ".");
			$data["13"] = number_format($row["13"], 0, ",", ".");
			$data["14"] = number_format($row["14"], 0, ",", ".");
			$data["15"] = number_format($row["15"], 0, ",", ".");
			$data["16"] = number_format($row["16"], 0, ",", ".");
			$data["17"] = number_format($row["17"], 0, ",", ".");
			$data["18"] = number_format($row["18"], 0, ",", ".");
			$data["19"] = number_format($row["19"], 0, ",", ".");
			$data["20"] = number_format($row["20"], 0, ",", ".");
			$data["21"] = number_format($row["21"], 0, ",", ".");
			$data["22"] = "<button type='button' class='btn btn-mini btn-success' style='width:65px;' onclick=\"window.location.href='index.php?page=mealorderpob&act=detail&date=".$datetime->indonesian_date($row["0"])."'\"><i class='icon-file icon-white'></i> DETAIL</button>";
		    $response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "mealorderpob" and $act == "new") {
		$primary = "t_header.id";
		
		$field = array (
			$primary,
		    "t_header.id",
		    "t_meal_order_pob.id",
		    "t_flight.id",
		    "concat(t_airline.code, '-', t_flight.flight_no)",
		    "concat(t_airline.code, '-', t_flight.flight_no, ' ', if(t_status.code != 'N/A', t_status.code, ''))",
		    "t_status.id",
		    "concat(t_status.name, ' (', t_status.code, ')')",
		    "t_config.id",
		    "t_config.register",
		    "t_aircraft.name",
		    "t_equipment.name",
		    "if(t_config.seat_fc is null, 'X', t_config.seat_fc)",
		    "if(t_config.seat_bc is null, 'X', t_config.seat_bc)",
		    "if(t_config.seat_yc is null, 'X', t_config.seat_yc)",
		    "if(t_config.seat_cr is null, 'X', t_config.seat_cr)",
		    "if(t_config.seat_cp is null, 'X', t_config.seat_cp)",
		    "if(t_meal_order_pob.mo_fc is null, 'X', t_meal_order_pob.mo_fc)",
		    "if(t_meal_order_pob.mo_bc is null, 'X', t_meal_order_pob.mo_bc)",
		    "if(t_meal_order_pob.mo_yc is null, 'X', t_meal_order_pob.mo_yc)",
		    "if(t_meal_order_pob.mo_cr is null, 'X', t_meal_order_pob.mo_cr)",
		    "if(t_meal_order_pob.mo_cp is null, 'X', t_meal_order_pob.mo_cp)",
		    "if(t_meal_order_pob.pob_fc is null, 'X', t_meal_order_pob.pob_fc)",
		    "if(t_meal_order_pob.pob_bc is null, 'X', t_meal_order_pob.pob_bc)",
		    "if(t_meal_order_pob.pob_yc is null, 'X', t_meal_order_pob.pob_yc)",
		    "if(t_meal_order_pob.pob_cr is null, 'X', t_meal_order_pob.pob_cr)",
		    "if(t_meal_order_pob.pob_cp is null, 'X', t_meal_order_pob.pob_cp)",
		    "if(t_meal_order_pob.spml_bbml is null, 'X', t_meal_order_pob.spml_bbml)",
		    "if(t_meal_order_pob.spml_ksml is null, 'X', t_meal_order_pob.spml_ksml)",
		    "if(t_meal_order_pob.spml_hcake is null, 'X', t_meal_order_pob.spml_hcake)"
		);
		
		$table = "t_header";
		$join = "left join t_flight on t_header.flight = t_flight.id left join t_airline on t_flight.airline = t_airline.id left join t_status on t_header.status = t_status.id left join t_meal_order_pob on t_header.id = t_meal_order_pob.header left join t_config on t_meal_order_pob.config = t_config.id left join t_aircraft on t_config.aircraft = t_aircraft.id left join t_equipment on t_config.equipment = t_equipment.id";
		
		$filter = "where t_header.date = '".$datetime->database_date($secure->sanitize($_GET["date"]))."'";
		
		$where = "";
		if (isset($_GET["sSearch"]) and $_GET["sSearch"] != "") {
		    foreach (array_slice($field, 2) as $column) {
		        $where .= $column." like '%".strtoupper($secure->sanitize($_GET["sSearch"]))."%' or ";
		    }
		    $where = $filter." and (".substr($where, 0, -3).")";
		} else {
			$where = $filter;
		}
		
		$order = "";
		if (isset($_GET["iSortCol_0"])) {
		    $order = "order by ";
		    for ($i = 0; $i < intval($secure->sanitize($_GET["iSortingCols"])); $i++) {
		        $order .= $field[$_GET["iSortCol_".$i]]." ".$secure->sanitize($_GET["sSortDir_".$i]).", ";
		    }
		    $order = substr_replace($order, "", -2);
		}
		
		$limit = "";
		if (isset($_GET["iDisplayStart"]) and $_GET["iDisplayLength"] != "-1") {
		    $limit = "limit ".intval($secure->sanitize($_GET["iDisplayStart"])).", ".intval($secure->sanitize($_GET["iDisplayLength"]));
		}
		
		$query = $mysqli->query("select sql_calc_found_rows ".implode(", ", $field)." from ".$table." ".$join." ".$where." ".$order." ".$limit);
		
		$filter = $mysqli->query("select found_rows()");
		$filter_row = $filter->fetch_row();
		
		$total = $mysqli->query("select count(".$primary.") from ".$table);
		$total_row = $total->fetch_row();
		
		$response = array(
	        "sEcho" => intval($_GET["sEcho"]),
	        "iTotalRecords" => intval($total_row["0"]),
	        "iTotalDisplayRecords" => intval($filter_row["0"]),
	        "aaData" => array()
	    );
		
		$data = array();
		$index = $_GET["iDisplayStart"]+1;
		while ($row = $query->fetch_row()) {
			$data["0"] = $index;
			$data["1"] = $row["1"];
			$data["2"] = $row["2"];
			$data["3"] = $row["3"];
			$data["4"] = $row["4"];
			$data["5"] = $row["5"];
			$data["6"] = $row["6"];
			$data["7"] = $row["7"];
			$data["8"] = $row["8"];
			$data["9"] = $row["9"];
			$data["10"] = $row["10"];
			$data["11"] =$row["11"];
			$data["12"] = $row["12"] == "X" ? $row["12"] : number_format($row["12"], 0, ",", ".");
			$data["13"] = $row["13"] == "X" ? $row["13"] : number_format($row["13"], 0, ",", ".");
			$data["14"] = $row["14"] == "X" ? $row["14"] : number_format($row["14"], 0, ",", ".");
			$data["15"] = $row["15"] == "X" ? $row["15"] : number_format($row["15"], 0, ",", ".");
			$data["16"] = $row["16"] == "X" ? $row["16"] : number_format($row["16"], 0, ",", ".");
			$data["17"] = $row["17"] == "X" ? $row["17"] : number_format($row["17"], 0, ",", ".");
			$data["18"] = $row["18"] == "X" ? $row["18"] : number_format($row["18"], 0, ",", ".");
			$data["19"] = $row["19"] == "X" ? $row["19"] : number_format($row["19"], 0, ",", ".");
			$data["20"] = $row["20"] == "X" ? $row["20"] : number_format($row["20"], 0, ",", ".");
			$data["21"] = $row["21"] == "X" ? $row["21"] : number_format($row["21"], 0, ",", ".");
			$data["22"] = $row["22"] == "X" ? $row["22"] : number_format($row["22"], 0, ",", ".");
			$data["23"] = $row["23"] == "X" ? $row["23"] : number_format($row["23"], 0, ",", ".");
			$data["24"] = $row["24"] == "X" ? $row["24"] : number_format($row["24"], 0, ",", ".");
			$data["25"] = $row["25"] == "X" ? $row["25"] : number_format($row["25"], 0, ",", ".");
			$data["26"] = $row["26"] == "X" ? $row["26"] : number_format($row["26"], 0, ",", ".");
			$data["27"] = $row["27"] == "X" ? $row["27"] : number_format($row["27"], 0, ",", ".");
			$data["28"] = $row["28"] == "X" ? $row["28"] : number_format($row["28"], 0, ",", ".");
			$data["29"] = $row["29"] == "X" ? $row["29"] : number_format($row["29"], 0, ",", ".");
			$data["30"] = "<button type='button' class='btn btn-mini btn-success' style='width:55px;' onclick=\"GetMealOrderPOB('".$row[2]."', '".$row[1]."', '".$row[3]."', '".$row[4]."', '".$row[6]."', '".$row[7]."', '".$row[8]."', '".$row[9]."', '".$row[10]."', '".$row[11]."', '".$row[12]."', '".$row[13]."', '".$row[14]."', '".$row[15]."', '".$row[16]."', '".$row[17]."', '".$row[18]."', '".$row[19]."', '".$row[20]."', '".$row[21]."', '".$row[22]."', '".$row[23]."', '".$row[24]."', '".$row[25]."', '".$row[26]."', '".$row[27]."', '".$row[28]."', '".$row[29]."')\"><i class='icon-edit icon-white'></i> EDIT</button>&nbsp;&nbsp;<button type='button' class='btn btn-mini btn-inverse' style='width:65px;' onclick=\"DeleteMealOrderPOB('".$row[1]."')\"><i class='icon-trash icon-white'></i> DELETE</button>";
			$response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "mealorderpob" and $act == "detail") {
		$primary = "t_header.id";
		
		$field = array (
			$primary,
		    "t_header.id",
		    "t_meal_order_pob.id",
		    "concat(t_airline.code, '-', t_flight.flight_no, ' ', if(t_status.code != 'N/A', t_status.code, ''))",
		    "t_config.register",
		    "if(t_meal_order_pob.mo_fc is null, 'X', t_meal_order_pob.mo_fc)",
		    "if(t_meal_order_pob.mo_bc is null, 'X', t_meal_order_pob.mo_bc)",
		    "if(t_meal_order_pob.mo_yc is null, 'X', t_meal_order_pob.mo_yc)",
		    "if(t_meal_order_pob.mo_cr is null, 'X', t_meal_order_pob.mo_cr)",
		    "if(t_meal_order_pob.pob_cp is null, 'X', t_meal_order_pob.mo_cp)",
		    "if(t_meal_order_pob.pob_fc is null, 'X', t_meal_order_pob.pob_fc)",
		    "if(t_meal_order_pob.pob_bc is null, 'X', t_meal_order_pob.pob_bc)",
		    "if(t_meal_order_pob.pob_yc is null, 'X', t_meal_order_pob.pob_yc)",
		    "if(t_meal_order_pob.pob_cr is null, 'X', t_meal_order_pob.pob_cr)",
		    "if(t_meal_order_pob.pob_cp is null, 'X', t_meal_order_pob.pob_cp)",
		    "if(t_meal_order_pob.spml_bbml is null, 'X', t_meal_order_pob.spml_bbml)",
		    "if(t_meal_order_pob.spml_ksml is null, 'X', t_meal_order_pob.spml_ksml)",
		    "if(t_meal_order_pob.spml_hcake is null, 'X', t_meal_order_pob.spml_hcake)"
		);
		
		$table = "t_header";
		$join = "left join t_flight on t_header.flight = t_flight.id left join t_airline on t_flight.airline = t_airline.id left join t_status on t_header.status = t_status.id left join t_meal_order_pob on t_header.id = t_meal_order_pob.header left join t_config on t_meal_order_pob.config = t_config.id";
		
		$filter = "where t_header.date = '".$datetime->database_date($secure->sanitize($_GET["date"]))."'";
		
		$where = "";
		if (isset($_GET["sSearch"]) and $_GET["sSearch"] != "") {
		    foreach (array_slice($field, 2) as $column) {
		        $where .= $column." like '%".strtoupper($secure->sanitize($_GET["sSearch"]))."%' or ";
		    }
		    $where = $filter." and (".substr($where, 0, -3).")";
		} else {
			$where = $filter;
		}
		
		$order = "";
		if (isset($_GET["iSortCol_0"])) {
		    $order = "order by ";
		    for ($i = 0; $i < intval($secure->sanitize($_GET["iSortingCols"])); $i++) {
		        $order .= $field[$_GET["iSortCol_".$i]]." ".$secure->sanitize($_GET["sSortDir_".$i]).", ";
		    }
		    $order = substr_replace($order, "", -2);
		}
		
		$limit = "";
		if (isset($_GET["iDisplayStart"]) and $_GET["iDisplayLength"] != "-1") {
		    $limit = "limit ".intval($secure->sanitize($_GET["iDisplayStart"])).", ".intval($secure->sanitize($_GET["iDisplayLength"]));
		}
		
		$query = $mysqli->query("select sql_calc_found_rows ".implode(", ", $field)." from ".$table." ".$join." ".$where." ".$order." ".$limit);
		
		$filter = $mysqli->query("select found_rows()");
		$filter_row = $filter->fetch_row();
		
		$total = $mysqli->query("select count(".$primary.") from ".$table);
		$total_row = $total->fetch_row();
		
		$response = array(
	        "sEcho" => intval($_GET["sEcho"]),
	        "iTotalRecords" => intval($total_row["0"]),
	        "iTotalDisplayRecords" => intval($filter_row["0"]),
	        "aaData" => array()
	    );
		
		$data = array();
		$index = $_GET["iDisplayStart"]+1;
		while ($row = $query->fetch_row()) {
			$data["0"] = $index;
			$data["1"] = $row["1"];
			$data["2"] = $row["2"];
			$data["3"] = $row["3"];
			$data["4"] = $row["4"];
			$data["5"] = $row["5"] == "X" ? $row["5"] : number_format($row["5"], 0, ",", ".");
			$data["6"] = $row["6"] == "X" ? $row["6"] : number_format($row["6"], 0, ",", ".");
			$data["7"] = $row["7"] == "X" ? $row["7"] : number_format($row["7"], 0, ",", ".");
			$data["8"] = $row["8"] == "X" ? $row["8"] : number_format($row["8"], 0, ",", ".");
			$data["9"] = $row["9"] == "X" ? $row["9"] : number_format($row["9"], 0, ",", ".");
			$data["10"] = $row["10"] == "X" ? $row["10"] : number_format($row["10"], 0, ",", ".");
			$data["11"] = $row["11"] == "X" ? $row["11"] : number_format($row["11"], 0, ",", ".");
			$data["12"] = $row["12"] == "X" ? $row["12"] : number_format($row["12"], 0, ",", ".");
			$data["13"] = $row["13"] == "X" ? $row["13"] : number_format($row["13"], 0, ",", ".");
			$data["14"] = $row["14"] == "X" ? $row["14"] : number_format($row["14"], 0, ",", ".");
			$data["15"] = $row["15"] == "X" ? $row["15"] : number_format($row["15"], 0, ",", ".");
			$data["16"] = $row["16"] == "X" ? $row["16"] : number_format($row["16"], 0, ",", ".");
			$data["17"] = $row["17"] == "X" ? $row["17"] : number_format($row["17"], 0, ",", ".");
			$response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "mealorderpob" and $act == "edit") {
		$primary = "t_header.id";
		
		$field = array (
			$primary,
		    "t_header.id",
		    "t_meal_order_pob.id",
		    "t_flight.id",
		    "concat(t_airline.code, '-', t_flight.flight_no)",
		    "concat(t_airline.code, '-', t_flight.flight_no, ' ', if(t_status.code != 'N/A', t_status.code, ''))",
		    "t_status.id",
		    "concat(t_status.name, ' (', t_status.code, ')')",
		    "t_config.id",
		    "t_config.register",
		    "t_aircraft.name",
		    "t_equipment.name",
		    "if(t_config.seat_fc is null, 'X', t_config.seat_fc)",
		    "if(t_config.seat_bc is null, 'X', t_config.seat_bc)",
		    "if(t_config.seat_yc is null, 'X', t_config.seat_yc)",
		    "if(t_config.seat_cr is null, 'X', t_config.seat_cr)",
		    "if(t_config.seat_cp is null, 'X', t_config.seat_cp)",
		    "if(t_meal_order_pob.mo_fc is null, 'X', t_meal_order_pob.mo_fc)",
		    "if(t_meal_order_pob.mo_bc is null, 'X', t_meal_order_pob.mo_bc)",
		    "if(t_meal_order_pob.mo_yc is null, 'X', t_meal_order_pob.mo_yc)",
		    "if(t_meal_order_pob.mo_cr is null, 'X', t_meal_order_pob.mo_cr)",
		    "if(t_meal_order_pob.mo_cp is null, 'X', t_meal_order_pob.mo_cp)",
		    "if(t_meal_order_pob.pob_fc is null, 'X', t_meal_order_pob.pob_fc)",
		    "if(t_meal_order_pob.pob_bc is null, 'X', t_meal_order_pob.pob_bc)",
		    "if(t_meal_order_pob.pob_yc is null, 'X', t_meal_order_pob.pob_yc)",
		    "if(t_meal_order_pob.pob_cr is null, 'X', t_meal_order_pob.pob_cr)",
		    "if(t_meal_order_pob.pob_cp is null, 'X', t_meal_order_pob.pob_cp)",
		    "if(t_meal_order_pob.spml_bbml is null, 'X', t_meal_order_pob.spml_bbml)",
		    "if(t_meal_order_pob.spml_ksml is null, 'X', t_meal_order_pob.spml_ksml)",
		    "if(t_meal_order_pob.spml_hcake is null, 'X', t_meal_order_pob.spml_hcake)"
		);
		
		$table = "t_header";
		$join = "left join t_flight on t_header.flight = t_flight.id left join t_airline on t_flight.airline = t_airline.id left join t_status on t_header.status = t_status.id left join t_meal_order_pob on t_header.id = t_meal_order_pob.header left join t_config on t_meal_order_pob.config = t_config.id left join t_aircraft on t_config.aircraft = t_aircraft.id left join t_equipment on t_config.equipment = t_equipment.id";
		
		$filter = "where t_header.date = '".$datetime->database_date($secure->sanitize($_GET["date"]))."'";
		
		$where = "";
		if (isset($_GET["sSearch"]) and $_GET["sSearch"] != "") {
		    foreach (array_slice($field, 2) as $column) {
		        $where .= $column." like '%".strtoupper($secure->sanitize($_GET["sSearch"]))."%' or ";
		    }
		    $where = $filter." and (".substr($where, 0, -3).")";
		} else {
			$where = $filter;
		}
		
		$order = "";
		if (isset($_GET["iSortCol_0"])) {
		    $order = "order by ";
		    for ($i = 0; $i < intval($secure->sanitize($_GET["iSortingCols"])); $i++) {
		        $order .= $field[$_GET["iSortCol_".$i]]." ".$secure->sanitize($_GET["sSortDir_".$i]).", ";
		    }
		    $order = substr_replace($order, "", -2);
		}
		
		$limit = "";
		if (isset($_GET["iDisplayStart"]) and $_GET["iDisplayLength"] != "-1") {
		    $limit = "limit ".intval($secure->sanitize($_GET["iDisplayStart"])).", ".intval($secure->sanitize($_GET["iDisplayLength"]));
		}
		
		$query = $mysqli->query("select sql_calc_found_rows ".implode(", ", $field)." from ".$table." ".$join." ".$where." ".$order." ".$limit);
		
		$filter = $mysqli->query("select found_rows()");
		$filter_row = $filter->fetch_row();
		
		$total = $mysqli->query("select count(".$primary.") from ".$table);
		$total_row = $total->fetch_row();
		
		$response = array(
	        "sEcho" => intval($_GET["sEcho"]),
	        "iTotalRecords" => intval($total_row["0"]),
	        "iTotalDisplayRecords" => intval($filter_row["0"]),
	        "aaData" => array()
	    );
		
		$data = array();
		$index = $_GET["iDisplayStart"]+1;
		while ($row = $query->fetch_row()) {
			$data["0"] = $index;
			$data["1"] = $row["1"];
			$data["2"] = $row["2"];
			$data["3"] = $row["3"];
			$data["4"] = $row["4"];
			$data["5"] = $row["5"];
			$data["6"] = $row["6"];
			$data["7"] = $row["7"];
			$data["8"] = $row["8"];
			$data["9"] = $row["9"];
			$data["10"] = $row["10"];
			$data["11"] =$row["11"];
			$data["12"] = $row["12"] == "X" ? $row["12"] : number_format($row["12"], 0, ",", ".");
			$data["13"] = $row["13"] == "X" ? $row["13"] : number_format($row["13"], 0, ",", ".");
			$data["14"] = $row["14"] == "X" ? $row["14"] : number_format($row["14"], 0, ",", ".");
			$data["15"] = $row["15"] == "X" ? $row["15"] : number_format($row["15"], 0, ",", ".");
			$data["16"] = $row["16"] == "X" ? $row["16"] : number_format($row["16"], 0, ",", ".");
			$data["17"] = $row["17"] == "X" ? $row["17"] : number_format($row["17"], 0, ",", ".");
			$data["18"] = $row["18"] == "X" ? $row["18"] : number_format($row["18"], 0, ",", ".");
			$data["19"] = $row["19"] == "X" ? $row["19"] : number_format($row["19"], 0, ",", ".");
			$data["20"] = $row["20"] == "X" ? $row["20"] : number_format($row["20"], 0, ",", ".");
			$data["21"] = $row["21"] == "X" ? $row["21"] : number_format($row["21"], 0, ",", ".");
			$data["22"] = $row["22"] == "X" ? $row["22"] : number_format($row["22"], 0, ",", ".");
			$data["23"] = $row["23"] == "X" ? $row["23"] : number_format($row["23"], 0, ",", ".");
			$data["24"] = $row["24"] == "X" ? $row["24"] : number_format($row["24"], 0, ",", ".");
			$data["25"] = $row["25"] == "X" ? $row["25"] : number_format($row["25"], 0, ",", ".");
			$data["26"] = $row["26"] == "X" ? $row["26"] : number_format($row["26"], 0, ",", ".");
			$data["27"] = $row["27"] == "X" ? $row["27"] : number_format($row["27"], 0, ",", ".");
			$data["28"] = $row["28"] == "X" ? $row["28"] : number_format($row["28"], 0, ",", ".");
			$data["29"] = $row["29"] == "X" ? $row["29"] : number_format($row["29"], 0, ",", ".");
			$data["30"] = "<button type='button' class='btn btn-mini btn-success' style='width:55px;' onclick=\"GetMealOrderPOB('".$row[2]."', '".$row[1]."', '".$row[3]."', '".$row[4]."', '".$row[6]."', '".$row[7]."', '".$row[8]."', '".$row[9]."', '".$row[10]."', '".$row[11]."', '".$row[12]."', '".$row[13]."', '".$row[14]."', '".$row[15]."', '".$row[16]."', '".$row[17]."', '".$row[18]."', '".$row[19]."', '".$row[20]."', '".$row[21]."', '".$row[22]."', '".$row[23]."', '".$row[24]."', '".$row[25]."', '".$row[26]."', '".$row[27]."', '".$row[28]."', '".$row[29]."')\"><i class='icon-edit icon-white'></i> EDIT</button>&nbsp;&nbsp;<button type='button' class='btn btn-mini btn-inverse' style='width:65px;' onclick=\"DeleteMealOrderPOB('".$row[1]."')\"><i class='icon-trash icon-white'></i> DELETE</button>";
			$response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "mealorderpob" and $act == "insert") {
		$id = $secure->sanitize($uri->request("id"));
		$header = $secure->sanitize($uri->request("header"));
		$date = $secure->sanitize($uri->request("date"));
		$ori_flight = $secure->sanitize($uri->request("ori_flight"));
		$flight = $secure->sanitize($uri->request("flight"));
		$ori_status = $secure->sanitize($uri->request("ori_status"));
		$status = $secure->sanitize($uri->request("status"));
		$config = $secure->sanitize($uri->request("config"));
		$mofc = str_replace(".", "", $secure->sanitize($uri->request("mofc")));
		$mobc = str_replace(".", "", $secure->sanitize($uri->request("mobc")));
		$moyc = str_replace(".", "", $secure->sanitize($uri->request("moyc")));
		$mocr = str_replace(".", "", $secure->sanitize($uri->request("mocr")));
		$mocp = str_replace(".", "", $secure->sanitize($uri->request("mocp")));
		$pobfc = str_replace(".", "", $secure->sanitize($uri->request("pobfc")));
		$pobbc = str_replace(".", "", $secure->sanitize($uri->request("pobbc")));
		$pobyc = str_replace(".", "", $secure->sanitize($uri->request("pobyc")));
		$pobcr = str_replace(".", "", $secure->sanitize($uri->request("pobcr")));
		$pobcp = str_replace(".", "", $secure->sanitize($uri->request("pobcp")));
		$bbml = str_replace(".", "", $secure->sanitize($uri->request("bbml")));
		$ksml = str_replace(".", "", $secure->sanitize($uri->request("ksml")));
		$hcake = str_replace(".", "", $secure->sanitize($uri->request("hcake")));
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		if ($ori_flight != $flight or $ori_status != $status) {
			$header_check_query = $mysqli->query("select t_header.id as id from t_header where t_header.date = '".$datetime->database_date($date)."' and t_header.flight = '".$flight."' and t_header.status = '".$status."'");
			$header_check_row = $header_check_query->num_rows;
			
			if ($header_check_row <= 0) {
				$header_query = $mysqli->query("select t_header.id as id from t_header where t_header.id = '".$header."'");
				$header_row = $header_query->num_rows;
				$h = $header_query->fetch_assoc();
				
				if ($header_row <= 0) {
					$insert = $mysqli->query("insert into t_header (date,
		                                 			  	  			flight,
		                                 			  	  			status) 
							                      			values ('".$datetime->database_date($date)."',
						                                  			'".$flight."',
						                                  			'".$status."')");
					
					$header_id = $mysqli->insert_id;
				} else {
					$update = $mysqli->query("update t_header set flight = '".$flight."',
		                                 			  	  		  status = '".$status."' 
							                      			where id = '".$h["id"]."'");
					
					$header_id = $h["id"];
				}
				
				$mealorderpob = $mysqli->query("select t_meal_order_pob.id as id from t_meal_order_pob where t_meal_order_pob.header = '".$header_id."'");
				$mealorderpob_row = $mealorderpob->num_rows;
				
				if ($mealorderpob_row <= 0) {
					$insert = $mysqli->query("insert into t_meal_order_pob (header,
																			config,
																			mo_fc,
					  								 			            mo_bc,
					  								 			            mo_yc,
					  								 			            mo_cr,
					  								 			            mo_cp,
					  								 			            pob_fc,
					  								 			            pob_bc,
					  								 			            pob_yc,
					  								 			            pob_cr,
					  								 			            pob_cp,
					  								 			            spml_bbml,
					  								 			            spml_ksml,
					  								 			            spml_hcake,
					                                 			  	    	user,
																  	    	modified) 
									                      	    	values ('".$header_id."',
									                      	    			'".$config."',
									                      	    			'".$mofc."',
								                                  	    	'".$mobc."',
								                                  	    	'".$moyc."',
								                                  	    	'".$mocr."',
								                                  	    	'".$mocp."',
								                                  	    	'".$pobfc."',
								                                  	    	'".$pobbc."',
								                                  	    	'".$pobyc."',
								                                  	    	'".$pobcr."',
								                                  	    	'".$pobcp."',
								                                  	    	'".$bbml."',
								                                  	    	'".$ksml."',
								                                  	    	'".$hcake."',
																  	    	'".$user."',
								                                  	    	'".$modified."')");
						
					if (!$insert) {
						print("error");
					} else {
						print("insert");
					}
				} else {
					$update = $mysqli->query("update t_meal_order_pob set header = '".$header_id."',
										                                  config = '".$config."',
										                                  mo_fc = '".$mofc."',
										                                  mo_bc = '".$mobc."',
								                                	  	  mo_yc = '".$moyc."',
								                                	  	  mo_cr = '".$mocr."',
								                                	  	  mo_cp = '".$mocp."',
								                                	  	  pob_fc = '".$pobfc."',
								                                	  	  pob_bc = '".$pobbc."',
								                                	  	  pob_yc = '".$pobyc."',
								                                	  	  pob_cr = '".$pobcr."',
								                                	  	  pob_cp = '".$pobcp."',
								                                	  	  spml_bbml = '".$bbml."',
								                                	  	  spml_ksml = '".$ksml."',
								                                	  	  spml_hcake = '".$hcake."',
															    	  	  user = '".$user."',
															    	  	  modified = '".$modified."'
								                          	    	where id = '".$id."'");
						
					if (!$update) {
						print("error");
					} else {
						print("insert");
					}
				}
			} else {
				print("exist");
			}
		} else {
			$header_query = $mysqli->query("select t_header.id as id from t_header where t_header.id = '".$header."'");
			$header_row = $header_query->num_rows;
			$h = $header_query->fetch_assoc();
			
			if ($header_row <= 0) {
				$insert = $mysqli->query("insert into t_header (date,
	                                 			  	  			flight,
	                                 			  	  			status) 
						                      			values ('".$datetime->database_date($date)."',
					                                  			'".$flight."',
					                                  			'".$status."')");
				
				$header_id = $mysqli->insert_id;
			} else {
				$update = $mysqli->query("update t_header set flight = '".$flight."',
	                                 			  	  		  status = '".$status."' 
						                      			where id = '".$h["id"]."'");
				
				$header_id = $h["id"];
			}
			
			$mealorderpob = $mysqli->query("select t_meal_order_pob.id as id from t_meal_order_pob where t_meal_order_pob.header = '".$header_id."'");
			$mealorderpob_row = $mealorderpob->num_rows;
			
			if ($mealorderpob_row <= 0) {
				$insert = $mysqli->query("insert into t_meal_order_pob (header,
																		config,
																		mo_fc,
				  								 			            mo_bc,
				  								 			            mo_yc,
				  								 			            mo_cr,
				  								 			            mo_cp,
				  								 			            pob_fc,
				  								 			            pob_bc,
				  								 			            pob_yc,
				  								 			            pob_cr,
				  								 			            pob_cp,
				  								 			            spml_bbml,
				  								 			            spml_ksml,
				  								 			            spml_hcake,
				                                 			  	    	user,
															  	    	modified) 
								                      	    	values ('".$header_id."',
								                      	    			'".$config."',
								                      	    			'".$mofc."',
							                                  	    	'".$mobc."',
							                                  	    	'".$moyc."',
							                                  	    	'".$mocr."',
							                                  	    	'".$mocp."',
							                                  	    	'".$pobfc."',
							                                  	    	'".$pobbc."',
							                                  	    	'".$pobyc."',
							                                  	    	'".$pobcr."',
							                                  	    	'".$pobcp."',
							                                  	    	'".$bbml."',
							                                  	    	'".$ksml."',
							                                  	    	'".$hcake."',
															  	    	'".$user."',
							                                  	    	'".$modified."')");
					
				if (!$insert) {
					print("error");
				} else {
					print("insert");
				}
			} else {
				$update = $mysqli->query("update t_meal_order_pob set header = '".$header_id."',
									                                  config = '".$config."',
									                                  mo_fc = '".$mofc."',
									                                  mo_bc = '".$mobc."',
							                                	  	  mo_yc = '".$moyc."',
							                                	  	  mo_cr = '".$mocr."',
							                                	  	  mo_cp = '".$mocp."',
							                                	  	  pob_fc = '".$pobfc."',
							                                	  	  pob_bc = '".$pobbc."',
							                                	  	  pob_yc = '".$pobyc."',
							                                	  	  pob_cr = '".$pobcr."',
							                                	  	  pob_cp = '".$pobcp."',
							                                	  	  spml_bbml = '".$bbml."',
							                                	  	  spml_ksml = '".$ksml."',
							                                	  	  spml_hcake = '".$hcake."',
														    	  	  user = '".$user."',
														    	  	  modified = '".$modified."'
							                          	    	where id = '".$id."'");
					
				if (!$update) {
					print("error");
				} else {
					print("insert");
				}
			}
		}
	} elseif ($page == "mealorderpob" and $act == "delete") {
		$header = $secure->sanitize($uri->request("header_delete"));
		
		$delete = $mysqli->query("delete t_header from t_header where t_header.id = '".$header."'");
		$delete = $mysqli->query("delete t_meal_order_pob from t_meal_order_pob where t_meal_order_pob.header = '".$header."'");
		$delete = $mysqli->query("delete t_production from t_production where t_production.header = '".$header."'");
		$delete = $mysqli->query("delete t_meal_uplift from t_meal_uplift where t_meal_uplift.header = '".$header."'");
		
		if (!$delete) {
			print("error");
		} else {
			print("delete");
		}
	} elseif ($page == "mealorderpob" and $act == "clear") {
		$date = $secure->sanitize($_GET["date"]);
		
		$delete = $mysqli->query("delete t_header from t_header where t_header.date = '".$datetime->database_date($date)."'");
		$delete = $mysqli->query("delete t_meal_order_pob from t_meal_order_pob left join t_header on t_meal_order_pob.header = t_header.id where t_header.date = '".$datetime->database_date($date)."'");
		$delete = $mysqli->query("delete t_production from t_production left join t_header on t_production.header = t_header.id where t_header.date = '".$datetime->database_date($date)."'");
		$delete = $mysqli->query("delete t_meal_uplift from t_meal_uplift left join t_header on t_meal_uplift.header = t_header.id where t_header.date = '".$datetime->database_date($date)."'");
		
		if (!$delete) {
			$_SESSION["mealorderpob"] = "ERROR OCCURED WHILE DELETING MEAL ORDER & P.O.B ON DATE <b>".$date."</b> !";
			header("location:../../index.php?page=".$page."&act=detail&date=".$date);
		} else {
			$_SESSION["mealorderpob"] = "ALL MEAL ORDER & P.O.B ON DATE <b>".$date."</b> HAS BEEN SUCCESSFULLY DELETED !";
			header("location:../../index.php?page=".$page);
		}
	}
}
?>