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
	
	if ($page == "mealuplift" and $act == "list") {
		$primary = "view_meal_uplift.date";
		
		$field = array (
			$primary,
		    "view_meal_uplift.date",
		    "view_meal_uplift.date",
		    "view_meal_uplift.airline",
		    "view_meal_uplift.total_flight",
		    "view_meal_uplift.total_fc",
		    "view_meal_uplift.total_bc",
		    "view_meal_uplift.total_yc",
		    "view_meal_uplift.total_cr",
		    "view_meal_uplift.total_cp",
		    "view_meal_uplift.remain_flight"
		);
		
		$table = "view_meal_uplift";
		$join = "";
		
		if ($_SESSION["user_privilege"] == "OPERATION GA" or $_SESSION["user_privilege"] == "OPERATION NONGA") {
			$filter = "where view_meal_uplift.airline = '".strtoupper($_SESSION["user_privilege"] == "OPERATION GA" ? "ga" : "non ga")."'";
			
			$where = "";
			if (isset($_GET["sSearch"]) and $_GET["sSearch"] != "") {
			    foreach (array_slice($field, 2) as $column) {
			        $where .= $column." like '%".strtoupper($secure->sanitize($_GET["sSearch"]))."%' or ";
			    }
			    $where = $filter." and (".substr($where, 0, -3).")";
			} else {
				$where = $filter;
			}
		} else {
			$where = "";
			if (isset($_GET["sSearch"]) and $_GET["sSearch"] != "") {
			    $where = "where ";
			    foreach (array_slice($field, 2) as $column) {
			        $where .= $column." like '%".strtoupper($secure->sanitize($_GET["sSearch"]))."%' or ";
			    }
			    $where = substr($where, 0, -3);
			}
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
			$data["4"] = number_format($row["4"], 0, ",", ".");
			$data["5"] = number_format($row["5"], 0, ",", ".");
			$data["6"] = number_format($row["6"], 0, ",", ".");
			$data["7"] = number_format($row["7"], 0, ",", ".");
			$data["8"] = number_format($row["8"], 0, ",", ".");
			$data["9"] = number_format($row["9"], 0, ",", ".");
			$data["10"] = $row["10"] == 0 ? "<span class='badge badge-success'>".number_format($row["10"], 0, ",", ".")."</span>" : "<span class='badge badge-important'>".number_format($row["10"], 0, ",", ".")."</span>";
			$data["11"] = "<button type='button' class='btn btn-mini btn-success' style='width:65px;' onclick=\"window.location.href='index.php?page=mealuplift&act=detail&date=".$datetime->indonesian_date($row["0"])."&airline=".strtolower(str_replace(" ", "", $row["3"]))."'\"><i class='icon-file icon-white'></i> DETAIL</button>";
		    $response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "mealuplift" and $act == "detail") {
		$primary = "t_header.id";
		
		$field = array (
			$primary,
		    "t_header.id",
		    "t_meal_uplift.id",
		    "concat(t_airline.code, '-', t_flight.flight_no, ' ', if(t_status.code != 'N/A', t_status.code, ''))",
		    "if(t_meal_uplift.fc is null, 'X', t_meal_uplift.fc)",
		    "if(t_meal_uplift.bc is null, 'X', t_meal_uplift.bc)",
		    "if(t_meal_uplift.yc is null, 'X', t_meal_uplift.yc)",
		    "if(t_meal_uplift.cr is null, 'X', t_meal_uplift.cr)",
		    "if(t_meal_uplift.cp is null, 'X', t_meal_uplift.cp)",
		    "if(t_meal_uplift.id is null, 'NEW', 'DONE')"
		);
		
		$table = "t_header";
		$join = "left join t_flight on t_header.flight = t_flight.id left join t_airline on t_flight.airline = t_airline.id left join t_status on t_header.status = t_status.id left join t_meal_uplift on t_header.id = t_meal_uplift.header";
		
		$filter = "where t_header.date = '".$datetime->database_date($secure->sanitize($_GET["date"]))."' and if(t_flight.airline = 1 or t_flight.airline = 31, 'GA', 'NON GA') = '".strtoupper($secure->sanitize($_GET["airline"]) == "nonga" ? "non ga" : "ga")."'";
		
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
			$data["4"] = $row["4"] == "X" ? $row["4"] : number_format($row["4"], 0, ",", ".");
			$data["5"] = $row["5"] == "X" ? $row["5"] : number_format($row["5"], 0, ",", ".");
			$data["6"] = $row["6"] == "X" ? $row["6"] : number_format($row["6"], 0, ",", ".");
			$data["7"] = $row["7"] == "X" ? $row["7"] : number_format($row["7"], 0, ",", ".");
			$data["8"] = $row["8"] == "X" ? $row["8"] : number_format($row["8"], 0, ",", ".");
			$data["9"] = $row["9"] == "NEW" ? "<span class='label label-important'>".$row["9"]."</span>" : "<span class='label label-success'>".$row["9"]."</span>";
			$response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "mealuplift" and $act == "edit") {
		$primary = "t_header.id";
		
		$field = array (
			$primary,
		    "t_header.id",
		    "t_meal_uplift.id",
		    "concat(t_airline.code, '-', t_flight.flight_no, ' ', if(t_status.code != 'N/A', t_status.code, ''))",
		    "t_status.name",
		    "if(t_meal_uplift.fc is null, 'X', t_meal_uplift.fc)",
		    "if(t_meal_uplift.bc is null, 'X', t_meal_uplift.bc)",
		    "if(t_meal_uplift.yc is null, 'X', t_meal_uplift.yc)",
		    "if(t_meal_uplift.cr is null, 'X', t_meal_uplift.cr)",
		    "if(t_meal_uplift.cp is null, 'X', t_meal_uplift.cp)",
		    "if(t_meal_uplift.id is null, 'NEW', 'DONE')"
		);
		
		$table = "t_header";
		$join = "left join t_flight on t_header.flight = t_flight.id left join t_airline on t_flight.airline = t_airline.id left join t_status on t_header.status = t_status.id left join t_meal_uplift on t_header.id = t_meal_uplift.header";
		
		$filter = "where t_header.date = '".$datetime->database_date($secure->sanitize($_GET["date"]))."' and if(t_flight.airline = 1 or t_flight.airline = 31, 'GA', 'NON GA') = '".strtoupper($secure->sanitize($_GET["airline"]) == "nonga" ? "non ga" : "ga")."'";
		
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
			$data["10"] = $row["10"] == "NEW" ? "<span class='label label-important'>".$row["10"]."</span>" : "<span class='label label-success'>".$row["10"]."</span>";
			$data["11"] = "<button type='button' class='btn btn-mini btn-success' style='width:65px;' onclick=\"GetMealUplift('".$row[2]."', '".$row[1]."', '".$row[3]."', '".$row[4]."', '".$row[5]."', '".$row[6]."', '".$row[7]."', '".$row[8]."', '".$row[9]."')\"><i class='icon-edit icon-white'></i> EDIT</button>";
		    $response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "mealuplift" and $act == "insert") {
		$id = $secure->sanitize($uri->request("id"));
		$header = $secure->sanitize($uri->request("header"));
		$fc = str_replace(".", "", $secure->sanitize($uri->request("fc")));
		$bc = str_replace(".", "", $secure->sanitize($uri->request("bc")));
		$yc = str_replace(".", "", $secure->sanitize($uri->request("yc")));
		$cr = str_replace(".", "", $secure->sanitize($uri->request("cr")));
		$cp = str_replace(".", "", $secure->sanitize($uri->request("cp")));
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		$query = $mysqli->query("select t_meal_uplift.id as id from t_meal_uplift where t_meal_uplift.header = '".$header."'");
		$row = $query->num_rows;
		
		if ($row <= 0) {
			$insert = $mysqli->query("insert into t_meal_uplift (header,
																 fc,
			  								 			         bc,
			  								 			         yc,
			  								 			         cr,
			  								 			         cp,
		                                 			  	    	 user,
													  	    	 modified) 
						                      	    	 values ('".$header."',
						                      	    	 		 '".$fc."',
					                                  	    	 '".$bc."',
					                                  	    	 '".$yc."',
					                                  	    	 '".$cr."',
					                                  	    	 '".$cp."',
													  	    	 '".$user."',
					                                  	    	 '".$modified."')");
			
			if (!$insert) {
				print("error");
			} else {
				print("insert");
			}
		} else {
			$update = $mysqli->query("update t_meal_uplift set header = '".$header."',
															   fc = '".$fc."',
						                                	   bc = '".$bc."',
						                                	   yc = '".$yc."',
						                                	   cr = '".$cr."',
						                                	   cp = '".$cp."',
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
}
?>