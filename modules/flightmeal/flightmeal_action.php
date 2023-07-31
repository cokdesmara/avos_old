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
	
	if ($page == "flightmeal" and $act == "list") {
		$primary = "date";
		
		$field = array (
			$primary,
		    "date",
		    "date",
		    "total_flight",
		    "total_mo_fc",
		    "total_mo_bc",
		    "total_mo_yc",
		    "total_mo_cr",
		    "total_mo_cp",
		    "total_pdt_fc",
		    "total_pdt_bc",
		    "total_pdt_yc",
		    "total_pdt_cr",
		    "total_pdt_cp",
		    "total_pdt_spr_fc",
		    "total_pdt_spr_bc",
		    "total_pdt_spr_yc",
		    "total_pdt_spr_cr",
		    "total_pdt_spr_cp",
		    "total_pdt_frz",
		    "total_mu_fc",
		    "total_mu_bc",
		    "total_mu_yc",
		    "total_mu_cr",
		    "total_mu_cp",
		    "total_pob_fc",
		    "total_pob_bc",
		    "total_pob_yc",
		    "total_pob_cr",
		    "total_pob_cp"
		);
		
		$table = "view_flight_meal";
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
			$data["22"] = number_format($row["22"], 0, ",", ".");
			$data["23"] = number_format($row["23"], 0, ",", ".");
			$data["24"] = number_format($row["24"], 0, ",", ".");
			$data["25"] = number_format($row["25"], 0, ",", ".");
			$data["26"] = number_format($row["26"], 0, ",", ".");
			$data["27"] = number_format($row["27"], 0, ",", ".");
			$data["28"] = number_format($row["28"], 0, ",", ".");
			$data["29"] = number_format($row["29"], 0, ",", ".");
			$data["30"] = "<button type='button' class='btn btn-mini btn-success' onclick=\"window.location.href='index.php?page=flightmeal&act=detail&date=".$datetime->indonesian_date($row["0"])."'\"><i class='icon-file icon-white'></i> DETAIL</button>";
			$response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "flightmeal" and $act == "detail") {
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
		    "meal_order_fc",
		    "meal_order_bc",
		    "meal_order_yc",
		    "meal_order_cr",
		    "meal_order_cp",
		    "production_fc",
		    "production_bc",
		    "production_yc",
		    "production_cr",
		    "production_cp",
		    "production_spr_fc",
		    "production_spr_bc",
		    "production_spr_yc",
		    "production_spr_cr",
		    "production_spr_cp",
		    "production_frozen",
		    "meal_uplift_fc",
		    "meal_uplift_bc",
		    "meal_uplift_yc",
		    "meal_uplift_cr",
		    "meal_uplift_cp",
		    "pax_on_board_fc",
		    "pax_on_board_bc",
		    "pax_on_board_yc",
		    "pax_on_board_cr",
		    "pax_on_board_cp"
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
			$data["27"] = number_format($row["27"], 0, ",", ".");
			$data["28"] = number_format($row["28"], 0, ",", ".");
			$data["29"] = number_format($row["29"], 0, ",", ".");
			$data["30"] = number_format($row["30"], 0, ",", ".");
			$data["31"] = number_format($row["31"], 0, ",", ".");
			$data["32"] = number_format($row["32"], 0, ",", ".");
			$data["33"] = number_format($row["33"], 0, ",", ".");
			$data["34"] = number_format($row["34"], 0, ",", ".");
			$response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	}
}
?>