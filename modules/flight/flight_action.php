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
	
	if ($page == "flight" and $act == "list") {
		$primary = "t_flight.id";
		
		$field = array (
			$primary,
		    "t_flight.id",
		    "t_airline.name",
		    "t_aviation.name",
		    "concat(t_airline.code, '-', t_flight.flight_no)",
		    "t_flight.destination",
		    "if(t_flight.active = 'Y', 'YES', 'NO')"
		);
		
		$table = "t_flight";
		$join = "left join t_airline on t_flight.airline = t_airline.id left join t_aviation on t_flight.aviation = t_aviation.id";
		
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
			$data["3"] = $row["3"];
			$data["4"] = $row["4"];
			$data["5"] = $row["5"];
			$data["6"] = $row["6"];
			$data["7"] = "<button type='button' class='btn btn-mini btn-success' style='width:65px;' onclick=\"window.location.href='index.php?page=flight&act=detail&id=".$row["0"]."'\"><i class='icon-file icon-white'></i> DETAIL</button>";
		    $response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "flight" and $act == "select") {
		$data = array();
		$query = $mysqli->query("select t_flight.id as id, t_airline.code as code, t_flight.flight_no as flight_no from t_flight left join t_airline on t_flight.airline = t_airline.id where concat(t_airline.code, '-', t_flight.flight_no) like '%".strtoupper($secure->sanitize($_GET["q"]))."%' and t_flight.active = 'Y' order by t_flight.id asc");
		
		while($r = $query->fetch_assoc()) {
			$data[] = array("id"=>$r["id"], "text"=>$r["code"]."-".$r["flight_no"]);
		}
		
		print($uri->json(json_encode($data)));
	} elseif ($page == "flight" and $act == "insert") {
		$airline = explode("|", $secure->sanitize($_POST["airline"]));
		$aviation = $secure->sanitize($_POST["aviation"]);
		$flight_no = $secure->sanitize($_POST["flight_no"]);
		$destination = strtoupper($secure->sanitize($_POST["from"]))."-".strtoupper($secure->sanitize($_POST["to"]));
		$active = $secure->sanitize($_POST["active"]);
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		$query = $mysqli->query("select t_flight.id as id from t_flight where t_flight.airline = '".$airline[0]."' and t_flight.flight_no = '".$flight_no."'");
		$row = $query->num_rows;
		
		if ($row <= 0) {
			$insert = $mysqli->query("insert into t_flight (airline,
															aviation,
															flight_no,
															destination,
			  								 			    active,
			                                 			  	user,
														  	modified) 
							                      	values ('".$airline[0]."',
							                      			'".$aviation."',
							                      	 		'".$flight_no."',
							                      	 		'".$destination."',
						                                  	'".$active."',
														  	'".$user."',
						                                  	'".$modified."')");
			
			if (!$insert) {
				$_SESSION["flight"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
				header("location:../../index.php?page=".$page."&act=new");
			} else {
				$id = $mysqli->insert_id;
				$_SESSION["flight"] = "FLIGHT <b>".$airline[1]."-".$flight_no."</b> HAS BEEN SUCCESSFULLY ADDED !";
				header("location:../../index.php?page=".$page."&act=detail&id=".$id);
			}
		} else {
			$_SESSION["flight"] = "FLIGHT <b>".$airline[1]."-".$flight_no."</b> IS ALREADY REGISTERED !";
			header("location:../../index.php?page=".$page."&act=new");
		}
	} elseif ($page == "flight" and $act == "update") {
		$id = $secure->sanitize($_POST["id"]);
		$ori_airline = $secure->sanitize($_POST["ori_airline"]);
		$airline = explode("|", $secure->sanitize($_POST["airline"]));
		$aviation = $secure->sanitize($_POST["aviation"]);
		$ori_flight_no = $secure->sanitize($_POST["ori_flight_no"]);
		$flight_no = $secure->sanitize($_POST["flight_no"]);
		$destination = strtoupper($secure->sanitize($_POST["from"]))."-".strtoupper($secure->sanitize($_POST["to"]));
		$active = $secure->sanitize($_POST["active"]);
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		if ($ori_airline != $airline[0] or $ori_flight_no != $flight_no) {
			$query = $mysqli->query("select t_flight.id as id from t_flight where t_flight.airline = '".$airline[0]."' and t_flight.flight_no = '".$flight_no."'");
			$row = $query->num_rows;
			
			if ($row <= 0) {
			  	$update = $mysqli->query("update t_flight set airline = '".$airline[0]."',
															  aviation = '".$aviation."',
															  flight_no = '".$flight_no."',
															  destination = '".$destination."',
							                                  active = '".$active."',
														      user = '".$user."',
														      modified = '".$modified."'
							                          	where id = '".$id."'");
				
				if (!$update) {
					$_SESSION["flight"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
					header("location:../../index.php?page=".$page."&act=edit&id=".$id);
				} else {
					$_SESSION["flight"] = "FLIGHT <b>".$airline[1]."-".$flight_no."</b> HAS BEEN SUCCESSFULLY UPDATED !";
					header("location:../../index.php?page=".$page."&act=detail&id=".$id);
				}
			} else {
				$_SESSION["flight"] = "FLIGHT <b>".$airline[1]."-".$flight_no."</b> IS ALREADY REGISTERED !";
				header("location:../../index.php?page=".$page."&act=edit&id=".$id);
			}
		} else {
			$update = $mysqli->query("update t_flight set airline = '".$airline[0]."',
														  aviation = '".$aviation."',
														  flight_no = '".$flight_no."',
														  destination = '".$destination."',
						                                  active = '".$active."',
													      user = '".$user."',
													      modified = '".$modified."'
						                          	where id = '".$id."'");
			
			if (!$update) {
				$_SESSION["flight"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
				header("location:../../index.php?page=".$page."&act=edit&id=".$id);
			} else {
				$_SESSION["flight"] = "FLIGHT <b>".$airline[1]."-".$flight_no."</b> HAS BEEN SUCCESSFULLY UPDATED !";
				header("location:../../index.php?page=".$page."&act=detail&id=".$id);
			}
		}
	}
}
?>