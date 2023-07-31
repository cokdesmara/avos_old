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
	
	if ($page == "status" and $act == "list") {
		$primary = "t_status.id";
		
		$field = array (
			$primary,
		    "t_status.id",
		    "t_status.code",
		    "t_status.name",
		    "if(t_status.active = 'Y', 'YES', 'NO')"
		);
		
		$table = "t_status";
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
			$data["2"] = $row["2"];
			$data["3"] = $row["3"];
			$data["4"] = $row["4"];
			$data["5"] = "<button type='button' class='btn btn-mini btn-success' style='width:65px;' onclick=\"window.location.href='index.php?page=status&act=detail&id=".$row["0"]."'\"><i class='icon-file icon-white'></i> DETAIL</button>";
		    $response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "status" and $act == "select") {
		$data = array();
		$query = $mysqli->query("select t_status.id as id, t_status.code as code, t_status.name as name from t_status where concat(t_status.name, ' (', t_status.code, ')') like '%".strtoupper($secure->sanitize($_GET["q"]))."%' and t_status.active = 'Y' order by t_status.id asc");
		
		while($r = $query->fetch_assoc()) {
			$data[] = array("id"=>$r["id"], "text"=>$r["name"]." (".$r["code"].")");
		}
		
		print($uri->json(json_encode($data)));
	} elseif ($page == "status" and $act == "insert") {
		$code = strtoupper($secure->sanitize($_POST["code"]));
		$name = strtoupper($secure->sanitize($_POST["name"]));
		$active = $secure->sanitize($_POST["active"]);
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		$query = $mysqli->query("select t_status.id as id from t_status where t_status.code = '".$code."' and t_status.name = '".$name."'");
		$row = $query->num_rows;
		
		if ($row <= 0) {
			$insert = $mysqli->query("insert into t_status (code,
															name,
			  								 			    active,
			                                 			  	user,
														  	modified) 
							                      	values ('".$code."',
							                      	 		'".$name."',
						                                  	'".$active."',
														  	'".$user."',
						                                  	'".$modified."')");
			
			if (!$insert) {
				$_SESSION["status"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
				header("location:../../index.php?page=".$page."&act=new");
			} else {
				$id = $mysqli->insert_id;
				$_SESSION["status"] = "STATUS <b>".$name." (".$code.")</b> HAS BEEN SUCCESSFULLY ADDED !";
				header("location:../../index.php?page=".$page."&act=detail&id=".$id);
			}
		} else {
			$_SESSION["status"] = "STATUS <b>".$name." (".$code.")</b> IS ALREADY REGISTERED !";
			header("location:../../index.php?page=".$page."&act=new");
		}
	} elseif ($page == "status" and $act == "update") {
		$id = $secure->sanitize($_POST["id"]);
		$ori_code = strtoupper($secure->sanitize($_POST["ori_code"]));
		$code = strtoupper($secure->sanitize($_POST["code"]));
		$ori_name = strtoupper($secure->sanitize($_POST["ori_name"]));
		$name = strtoupper($secure->sanitize($_POST["name"]));
		$active = $secure->sanitize($_POST["active"]);
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		if ($ori_code != $code or $ori_name != $name) {
			$query = $mysqli->query("select t_status.id as id from t_status where t_status.code = '".$code."' and t_status.name = '".$name."'");
			$row = $query->num_rows;
			
			if ($row <= 0) {
			  	$update = $mysqli->query("update t_status set code = '".$code."',
															  name = '".$name."',
							                                  active = '".$active."',
														      user = '".$user."',
														      modified = '".$modified."'
							                          	where id = '".$id."'");
				
				if (!$update) {
					$_SESSION["status"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
					header("location:../../index.php?page=".$page."&act=edit&id=".$id);
				} else {
					$_SESSION["status"] = "STATUS <b>".$name." (".$code.")</b> HAS BEEN SUCCESSFULLY UPDATED !";
					header("location:../../index.php?page=".$page."&act=detail&id=".$id);
				}
			} else {
				$_SESSION["status"] = "STATUS <b>".$name." (".$code.")</b> IS ALREADY REGISTERED !";
				header("location:../../index.php?page=".$page."&act=edit&id=".$id);
			}
		} else {
			$update = $mysqli->query("update t_status set code = '".$code."',
														  name = '".$name."',
						                                  active = '".$active."',
													      user = '".$user."',
													      modified = '".$modified."'
						                          	where id = '".$id."'");
			
			if (!$update) {
				$_SESSION["status"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
				header("location:../../index.php?page=".$page."&act=edit&id=".$id);
			} else {
				$_SESSION["status"] = "STATUS <b>".$name." (".$code.")</b> HAS BEEN SUCCESSFULLY UPDATED !";
				header("location:../../index.php?page=".$page."&act=detail&id=".$id);
			}
		}
	}
}
?>