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
	
	if ($page == "production" and $act == "list") {
		$primary = "view_production.date";
		
		$field = array (
			$primary,
		    "view_production.date",
		    "view_production.date",
		    "view_production.total_flight",
		    "view_production.total_fc",
		    "view_production.total_bc",
		    "view_production.total_yc",
		    "view_production.total_cr",
		    "view_production.total_cp",
		    "view_production.total_spare_fc",
		    "view_production.total_spare_bc",
		    "view_production.total_spare_yc",
		    "view_production.total_spare_cr",
		    "view_production.total_spare_cp",
		    "view_production.total_frozen",
		    "view_production.remain_flight"
		);
		
		$table = "view_production";
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
			$data["15"] = $row["15"] == 0 ? "<span class='badge badge-success'>".number_format($row["15"], 0, ",", ".")."</span>" : "<span class='badge badge-important'>".number_format($row["15"], 0, ",", ".")."</span>";
			$data["16"] = "<button type='button' class='btn btn-mini btn-success' style='width:65px;' onclick=\"window.location.href='index.php?page=production&act=detail&date=".$datetime->indonesian_date($row["0"])."'\"><i class='icon-file icon-white'></i> DETAIL</button>";
		    $response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "production" and $act == "detail") {
		$primary = "t_header.id";
		
		$field = array (
			$primary,
		    "t_header.id",
		    "t_production.id",
		    "concat(t_airline.code, '-', t_flight.flight_no, ' ', if(t_status.code != 'N/A', t_status.code, ''))",
		    "if(t_production.fc is null, 'X', t_production.fc)",
		    "if(t_production.bc is null, 'X', t_production.bc)",
		    "if(t_production.yc is null, 'X', t_production.yc)",
		    "if(t_production.cr is null, 'X', t_production.cr)",
		    "if(t_production.cp is null, 'X', t_production.cp)",
		    "if(t_production.spare_fc is null, 'X', t_production.spare_fc)",
		    "if(t_production.spare_bc is null, 'X', t_production.spare_bc)",
		    "if(t_production.spare_yc is null, 'X', t_production.spare_yc)",
		    "if(t_production.spare_cr is null, 'X', t_production.spare_cr)",
		    "if(t_production.spare_cp is null, 'X', t_production.spare_cp)",
		    "if(t_production.frozen is null, 'X', t_production.frozen)",
		    "if(t_production.id is null, 'NEW', 'DONE')"
		);
		
		$table = "t_header";
		$join = "left join t_flight on t_header.flight = t_flight.id left join t_airline on t_flight.airline = t_airline.id left join t_status on t_header.status = t_status.id left join t_production on t_header.id = t_production.header";
		
		$filter = "where t_header.date = '".$datetime->database_date($_GET["date"])."'";
		
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
			$data["9"] = $row["9"] == "X" ? $row["9"] : number_format($row["9"], 0, ",", ".");
			$data["10"] = $row["10"] == "X" ? $row["10"] : number_format($row["10"], 0, ",", ".");
			$data["11"] = $row["11"] == "X" ? $row["11"] : number_format($row["11"], 0, ",", ".");
			$data["12"] = $row["12"] == "X" ? $row["12"] : number_format($row["12"], 0, ",", ".");
			$data["13"] = $row["13"] == "X" ? $row["13"] : number_format($row["13"], 0, ",", ".");
			$data["14"] = $row["14"] == "X" ? $row["14"] : number_format($row["14"], 0, ",", ".");
			$data["15"] = $row["15"] == "NEW" ? "<span class='label label-important'>".$row["15"]."</span>" : "<span class='label label-success'>".$row["15"]."</span>";
			$response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "production" and $act == "edit") {
		$primary = "t_header.id";
		
		$field = array (
			$primary,
		    "t_header.id",
		    "t_production.id",
		    "concat(t_airline.code, '-', t_flight.flight_no, ' ', if(t_status.code != 'N/A', t_status.code, ''))",
		    "t_status.name",
		    "if(t_production.fc is null, 'X', t_production.fc)",
		    "if(t_production.bc is null, 'X', t_production.bc)",
		    "if(t_production.yc is null, 'X', t_production.yc)",
		    "if(t_production.cr is null, 'X', t_production.cr)",
		    "if(t_production.cp is null, 'X', t_production.cp)",
		    "if(t_production.spare_fc is null, 'X', t_production.spare_fc)",
		    "if(t_production.spare_bc is null, 'X', t_production.spare_bc)",
		    "if(t_production.spare_yc is null, 'X', t_production.spare_yc)",
		    "if(t_production.spare_cr is null, 'X', t_production.spare_cr)",
		    "if(t_production.spare_cp is null, 'X', t_production.spare_cp)",
		    "if(t_production.frozen is null, 'X', t_production.frozen)",
		    "if(t_production.id is null, 'NEW', 'DONE')"
		);
		
		$table = "t_header";
		$join = "left join t_flight on t_header.flight = t_flight.id left join t_airline on t_flight.airline = t_airline.id left join t_status on t_header.status = t_status.id left join t_production on t_header.id = t_production.header";
		
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
			$data["16"] = $row["16"] == "NEW" ? "<span class='label label-important'>".$row["16"]."</span>" : "<span class='label label-success'>".$row["16"]."</span>";
			$data["17"] = "<button type='button' class='btn btn-mini btn-success' style='width:65px;' onclick=\"GetProduction('".$row[2]."', '".$row[1]."', '".$row[3]."', '".$row[4]."', '".$row[5]."', '".$row[6]."', '".$row[7]."', '".$row[8]."', '".$row[9]."', '".$row[10]."', '".$row[11]."', '".$row[12]."', '".$row[13]."', '".$row[14]."', '".$row[15]."')\"><i class='icon-edit icon-white'></i> EDIT</button>";
			$response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "production" and $act == "insert") {
		$id = $secure->sanitize($uri->request("id"));
		$header = $secure->sanitize($uri->request("header"));
		$fc = str_replace(".", "", $secure->sanitize($uri->request("fc")));
		$bc = str_replace(".", "", $secure->sanitize($uri->request("bc")));
		$yc = str_replace(".", "", $secure->sanitize($uri->request("yc")));
		$cr = str_replace(".", "", $secure->sanitize($uri->request("cr")));
		$cp = str_replace(".", "", $secure->sanitize($uri->request("cp")));
		$sfc = str_replace(".", "", $secure->sanitize($uri->request("sfc")));
		$sbc = str_replace(".", "", $secure->sanitize($uri->request("sbc")));
		$syc = str_replace(".", "", $secure->sanitize($uri->request("syc")));
		$scr = str_replace(".", "", $secure->sanitize($uri->request("scr")));
		$scp = str_replace(".", "", $secure->sanitize($uri->request("scp")));
		$frz = str_replace(".", "", $secure->sanitize($uri->request("frz")));
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		$query = $mysqli->query("select t_production.id as id from t_production where t_production.header = '".$header."'");
		$row = $query->num_rows;
		
		if ($row <= 0) {
			$insert = $mysqli->query("insert into t_production (header,
																fc,
			  								 			        bc,
			  								 			        yc,
			  								 			        cr,
			  								 			        cp,
			  								 			        spare_fc,
			  								 			        spare_bc,
			  								 			        spare_yc,
			  								 			        spare_cr,
			  								 			        spare_cp,
			  								 			        frozen,
		                                 			  	    	user,
													  	    	modified) 
						                      	    	values ('".$header."',
						                      	    			'".$fc."',
					                                  	    	'".$bc."',
					                                  	    	'".$yc."',
					                                  	    	'".$cr."',
					                                  	    	'".$cp."',
					                                  	    	'".$sfc."',
					                                  	    	'".$sbc."',
					                                  	    	'".$syc."',
					                                  	    	'".$scr."',
					                                  	    	'".$scp."',
					                                  	    	'".$frz."',
													  	    	'".$user."',
					                                  	    	'".$modified."')");
				
			if (!$insert) {
				print("error");
			} else {
				print("insert");
			}
		} else {
			$update = $mysqli->query("update t_production set header = '".$header."',
															  fc = '".$fc."',
						                                	  bc = '".$bc."',
						                                	  yc = '".$yc."',
						                                	  cr = '".$cr."',
						                                	  cp = '".$cp."',
						                                	  spare_fc = '".$sfc."',
						                                	  spare_bc = '".$sbc."',
						                                	  spare_yc = '".$syc."',
						                                	  spare_cr = '".$scr."',
						                                	  spare_cp = '".$scp."',
						                                	  frozen = '".$frz."',
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