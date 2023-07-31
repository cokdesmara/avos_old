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
	
	if ($page == "config" and $act == "list") {
		$primary = "t_config.id";
		
		$field = array (
			$primary,
		    "t_config.id",
		    "t_config.register",
		    "t_aircraft.name",
		    "t_equipment.name",
		    "t_config.seat_fc",
		    "t_config.seat_bc",
		    "t_config.seat_yc",
		    "t_config.seat_cr",
		    "t_config.seat_cp",
		    "if(t_config.active = 'Y', 'YES', 'NO')"
		);
		
		$table = "t_config";
		$join = "left join t_aircraft on t_config.aircraft = t_aircraft.id left join t_equipment on t_config.equipment = t_equipment.id";
		
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
			$data["7"] = $row["7"];
			$data["8"] = $row["8"];
			$data["9"] = $row["9"];
			$data["10"] = $row["10"];
			$data["11"] = "<button type='button' class='btn btn-mini btn-success' style='width:65px;' onclick=\"window.location.href='index.php?page=config&act=detail&id=".$row["0"]."'\"><i class='icon-file icon-white'></i> DETAIL</button>";
		    $response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "config" and $act == "select") {
		$data = array();
		$query = $mysqli->query("select t_config.id as id, t_config.register as register from t_config where t_config.register like '%".strtoupper($secure->sanitize($_GET["q"]))."%' and t_config.active = 'Y' order by t_config.register asc");
		
		while($r = $query->fetch_assoc()) {
			$data[] = array("id"=>$r["id"], "text"=>$r["register"]);
		}
		
		print($uri->json(json_encode($data)));
	} elseif ($page == "config" and $act == "choose") {
		$data = array();
		$query = $mysqli->query("select t_aircraft.name as aircraft, t_equipment.name as equipment, t_config.seat_fc as seat_fc, t_config.seat_bc as seat_bc, t_config.seat_yc as seat_yc, t_config.seat_cr as seat_cr, t_config.seat_cp as seat_cp from t_config left join t_aircraft on t_config.aircraft = t_aircraft.id left join t_equipment on t_config.equipment = t_equipment.id where t_config.id = '".$secure->sanitize($_GET["config"])."' and t_config.active = 'Y'");
		
		while($r = $query->fetch_assoc()) {
			$data[] = array("aircraft"=>$r["aircraft"], "equipment"=>$r["equipment"], "seat_fc"=>$r["seat_fc"], "seat_bc"=>$r["seat_bc"], "seat_yc"=>$r["seat_yc"], "seat_cr"=>$r["seat_cr"], "seat_cp"=>$r["seat_cp"]);
		}
		
		print($uri->json(json_encode($data)));
	} elseif ($page == "config" and $act == "insert") {
		$register = strtoupper($secure->sanitize($_POST["register"]));
		$aircraft = $secure->sanitize($_POST["aircraft"]);
		$equipment = $secure->sanitize($_POST["equipment"]);
		$seat_fc = str_replace(".", "", $secure->sanitize($_POST["seat_fc"]));
		$seat_bc = str_replace(".", "", $secure->sanitize($_POST["seat_bc"]));
		$seat_yc = str_replace(".", "", $secure->sanitize($_POST["seat_yc"]));
		$seat_cr = str_replace(".", "", $secure->sanitize($_POST["seat_cr"]));
		$seat_cp = str_replace(".", "", $secure->sanitize($_POST["seat_cp"]));
		$active = $secure->sanitize($_POST["active"]);
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		$query = $mysqli->query("select t_config.register as register from t_config where t_config.register = '".$register."'");
		$row = $query->num_rows;
		
		if ($row <= 0) {
			$insert = $mysqli->query("insert into t_config (register,
															aircraft,
															equipment,
															seat_fc,
															seat_bc,
															seat_yc,
															seat_cr,
															seat_cp,
			  								 			    active,
			                                 			  	user,
														  	modified) 
							                      	values ('".$register."',
							                      	 		'".$aircraft."',
							                      	 		'".$equipment."',
							                      	 		'".$seat_fc."',
							                      	 		'".$seat_bc."',
							                      	 		'".$seat_yc."',
							                      	 		'".$seat_cr."',
							                      	 		'".$seat_cp."',
						                                  	'".$active."',
														  	'".$user."',
						                                  	'".$modified."')");
			
			if (!$insert) {
				$_SESSION["config"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
				header("location:../../index.php?page=".$page."&act=new");
			} else {
				$id = $mysqli->insert_id;
				$_SESSION["config"] = "CONFIG REGISTER <b>".$register."</b> REV <b>".$revision."</b> HAS BEEN SUCCESSFULLY ADDED !";
				header("location:../../index.php?page=".$page."&act=detail&id=".$id);
			}
		} else {
			$_SESSION["config"] = "CONFIG REGISTER <b>".$register."</b> REV <b>".$revision."</b> IS ALREADY REGISTERED !";
			header("location:../../index.php?page=".$page."&act=new");
		}
	} elseif ($page == "config" and $act == "update") {
		$id = $secure->sanitize($_POST["id"]);
		$ori_register = strtoupper($secure->sanitize($_POST["ori_register"]));
		$register = strtoupper($secure->sanitize($_POST["register"]));
		$aircraft = $secure->sanitize($_POST["aircraft"]);
		$equipment = $secure->sanitize($_POST["equipment"]);
		$seat_fc = str_replace(".", "", $secure->sanitize($_POST["seat_fc"]));
		$seat_bc = str_replace(".", "", $secure->sanitize($_POST["seat_bc"]));
		$seat_yc = str_replace(".", "", $secure->sanitize($_POST["seat_yc"]));
		$seat_cr = str_replace(".", "", $secure->sanitize($_POST["seat_cr"]));
		$seat_cp = str_replace(".", "", $secure->sanitize($_POST["seat_cp"]));
		$active = $secure->sanitize($_POST["active"]);
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		if ($ori_register != $register or $ori_revision != $revision) {
			$query = $mysqli->query("select t_config.register as register from t_config where t_config.register = '".$register."'");
			$row = $query->num_rows;
			
			if ($row <= 0) {
			  	$update = $mysqli->query("update t_config set register = '".$register."',
															  aircraft = '".$aircraft."',
															  equipment = '".$equipment."',
															  seat_fc = '".$seat_fc."',
															  seat_bc = '".$seat_bc."',
															  seat_yc = '".$seat_yc."',
															  seat_cr = '".$seat_cr."',
															  seat_cp = '".$seat_cp."',
							                                  active = '".$active."',
														      user = '".$user."',
														      modified = '".$modified."'
							                          	where id = '".$id."'");
				
				if (!$update) {
					$_SESSION["config"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
					header("location:../../index.php?page=".$page."&act=edit&id=".$id);
				} else {
					$_SESSION["config"] = "CONFIG REGISTER <b>".$register."</b> REV <b>".$revision."</b> HAS BEEN SUCCESSFULLY UPDATED !";
					header("location:../../index.php?page=".$page."&act=detail&id=".$id);
				}
			} else {
				$_SESSION["config"] = "CONFIG REGISTER <b>".$register."</b> REV <b>".$revision."</b> IS ALREADY REGISTERED !";
				header("location:../../index.php?page=".$page."&act=edit&id=".$id);
			}
		} else {
			$update = $mysqli->query("update t_config set register = '".$register."',
														  aircraft = '".$aircraft."',
														  equipment = '".$equipment."',
														  seat_fc = '".$seat_fc."',
														  seat_bc = '".$seat_bc."',
														  seat_yc = '".$seat_yc."',
														  seat_cr = '".$seat_cr."',
														  seat_cp = '".$seat_cp."',
						                                  active = '".$active."',
													      user = '".$user."',
													      modified = '".$modified."'
						                          	where id = '".$id."'");
			
			if (!$update) {
				$_SESSION["config"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
				header("location:../../index.php?page=".$page."&act=edit&id=".$id);
			} else {
				$_SESSION["config"] = "CONFIG REGISTER <b>".$register."</b> REV <b>".$revision."</b> HAS BEEN SUCCESSFULLY UPDATED !";
				header("location:../../index.php?page=".$page."&act=detail&id=".$id);
			}
		}
	}
}
?>