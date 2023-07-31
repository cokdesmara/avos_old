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
	
	if ($page == "log" and $act == "list") {
		$primary = "t_log.id";
		
		$field = array (
			$primary,
		    "t_log.id",
		    "t_user.name",
		    "t_user.email",
		    "t_user.privilege",
		    "if(t_user.active = 'Y', 'YES', 'NO')",
		    "t_log.login",
		    "t_log.login"
		);
		
		$table = "t_log";
		$join = "left join t_user on t_log.user = t_user.id";
		
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
			$data["2"] = $row["2"];
			$data["3"] = "<a href='mailto:".strtolower($row["3"])."'>".$row["3"]."</a>";
			$data["4"] = $row["4"];
			$data["5"] = $row["5"];
			$data["6"] = strtoupper($datetime->time_ago($row["6"]));
			$data["7"] = $datetime->indonesian_datetime($row["7"]);
		    $response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "log" and $act == "clear") {
		$clear = $mysqli->query("truncate t_log");
		
		if (!$clear) {
			$_SESSION["log"] = "ERROR OCCURED WHILE DELETING DATA !";
			$_SESSION["alert"] = "alert-error";
			header("location:../../index.php?page=".$page);
		} else {
			$_SESSION["log"] = "ALL USER LOG HAS BEEN SUCCESSFULLY CLEARED !";
			$_SESSION["alert"] = "alert-success";
			header("location:../../index.php?page=".$page);
		}
	}
}
?>