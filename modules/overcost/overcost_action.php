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
	
	if ($page == "overcost" and $act == "list") {
		$primary = "date";
		
		$field = array (
			$primary,
		    "date",
		    "date",
		    "total_flight",
		    "total_os_fc",
		    "total_os_bc",
		    "total_os_yc",
		    "total_os_cr",
		    "total_os_cp",
		    "(total_os_fc + total_os_bc + total_os_yc + total_os_cr + total_os_cp)",
		    "total_op_fc",
		    "total_op_bc",
		    "total_op_yc",
		    "total_op_cr",
		    "total_op_cp",
		    "(total_op_fc + total_op_bc + total_op_yc + total_op_cr + total_op_cp)",
		    "total_wm_fc",
		    "total_wm_bc",
		    "total_wm_yc",
		    "total_wm_cr",
		    "total_wm_cp",
		    "(total_wm_fc + total_wm_bc + total_wm_yc + total_wm_cr + total_wm_cp)"
		);
		
		$table = "view_overcost";
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
			$data["22"] = "<button type='button' class='btn btn-mini btn-success' onclick=\"window.location.href='index.php?page=overcost&act=detail&date=".$datetime->indonesian_date($row["0"])."'\"><i class='icon-file icon-white'></i> DETAIL</button>";
			$response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "overcost" and $act == "detail") {
		$primary = "id";
		
		$field = array (
			$primary,
		    "date",
		    "date",
		    "aviation",
		    "flight",
		    "destination",
		    "register",
		    "aircraft",
		    "equipment",
		    "over_supply_fc",
		    "over_supply_bc",
		    "over_supply_yc",
		    "over_supply_cr",
		    "over_supply_cp",
		    "(over_supply_fc + over_supply_bc + over_supply_yc + over_supply_cr + over_supply_cp)",
		    "over_production_fc",
		    "over_production_bc",
		    "over_production_yc",
		    "over_production_cr",
		    "over_production_cp",
		    "(over_production_fc + over_production_bc + over_production_yc + over_production_cr + over_production_cp)",
		    "wasted_meal_fc",
		    "wasted_meal_bc",
		    "wasted_meal_yc",
		    "wasted_meal_cr",
		    "wasted_meal_cp",
		    "(wasted_meal_fc + wasted_meal_bc + wasted_meal_yc + wasted_meal_cr + wasted_meal_cr)"
		);
		
		$table = "view_summary_overcost";
		$join = "";
		
		$filter = "where date = '".$datetime->database_date($secure->sanitize($_GET["date"]))."'";
		
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
			$data["2"] = $datetime->indonesian_date($row["2"]);
			$data["3"] = $row["3"];
			$data["4"] = $row["4"];
			$data["5"] = $row["5"];
			$data["6"] = $row["6"];
			$data["7"] = $row["7"];
			$data["8"] = $row["8"];
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
			$data["22"] = number_format($row["22"], 0, ",", ".");
			$data["23"] = number_format($row["23"], 0, ",", ".");
			$data["24"] = number_format($row["24"], 0, ",", ".");
			$data["25"] = number_format($row["25"], 0, ",", ".");
			$data["26"] = number_format($row["26"], 0, ",", ".");
			$response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	}
}
?>