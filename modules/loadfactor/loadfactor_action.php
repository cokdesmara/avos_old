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
	
	if ($page == "loadfactor" and $act == "list") {
		$primary = "date";
		
		$field = array (
			$primary,
		    "date",
		    "date",
		    "total_flight",
		    "total_pob_fc",
		    "total_pob_bc",
		    "total_pob_yc",
		    "total_pob_cr",
		    "total_pob_cp",
		    "(total_pob_fc + total_pob_bc + total_pob_yc + total_pob_cr + total_pob_cp)",
		    "total_config_fc",
		    "total_config_bc",
		    "total_config_yc",
		    "total_config_cr",
		    "total_config_cp",
		    "(total_config_fc + total_config_bc + total_config_yc + total_config_cr + total_config_cp)",
		    "total_lf_fc",
		    "total_lf_bc",
		    "total_lf_yc",
		    "total_lf_cr",
		    "total_lf_cp",
		    "round((total_pob_fc + total_pob_bc + total_pob_yc + total_pob_cr + total_pob_cp) / (total_config_fc + total_config_bc + total_config_yc + total_config_cr + total_config_cp) * 100, 2)"
		);
		
		$table = "view_load_factor";
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
			$data["16"] = $row["16"]." %";
			$data["17"] = $row["17"]." %";
			$data["18"] = $row["18"]." %";
			$data["19"] = $row["19"]." %";
			$data["20"] = $row["20"]." %";
			$data["21"] = $row["21"]." %";
			$data["22"] = "<button type='button' class='btn btn-mini btn-success' onclick=\"window.location.href='index.php?page=loadfactor&act=detail&date=".$datetime->indonesian_date($row["0"])."'\"><i class='icon-file icon-white'></i> DETAIL</button>";
			$response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "loadfactor" and $act == "detail") {
		$primary = "date";
		
		$field = array (
			$primary,
		    "date",
		    "date",
		    "code",
		    "aviation",
		    "total_flight",
		    "total_pob_fc",
		    "total_pob_bc",
		    "total_pob_yc",
		    "total_pob_cr",
		    "total_pob_cp",
		     "(total_pob_fc + total_pob_bc + total_pob_yc + total_pob_cr + total_pob_cp)",
		    "total_config_fc",
		    "total_config_bc",
		    "total_config_yc",
		    "total_config_cr",
		    "total_config_cp",
		    "(total_config_fc + total_config_bc + total_config_yc + total_config_cr + total_config_cp)",
		    "total_lf_fc",
		    "total_lf_bc",
		    "total_lf_yc",
		    "total_lf_cr",
		    "total_lf_cp",
		    "round((total_pob_fc + total_pob_bc + total_pob_yc + total_pob_cr + total_pob_cp) / (total_config_fc + total_config_bc + total_config_yc + total_config_cr + total_config_cp) * 100, 2)"
		);
		
		$table = "view_load_factor_detail";
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
			$data["18"] = $row["18"]." %";
			$data["19"] = $row["19"]." %";
			$data["20"] = $row["20"]." %";
			$data["21"] = $row["21"]." %";
			$data["22"] = $row["22"]." %";
			$data["23"] = $row["23"]." %";
			$response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	}
}
?>