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
	
	if ($page == "user" and $act == "list") {
		$primary = "t_user.id";
		
		$field = array (
			$primary,
			"t_user.id",
		    "t_user.name",
		    "t_user.email",
		    "t_user.privilege",
		    "if(t_user.active = 'Y', 'YES', 'NO')"
		);
		
		$table = "t_user";
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
			$data["3"] = "<a href='mailto:".strtolower($row["3"])."'>".$row["3"]."</a>";
			$data["4"] = $row["4"];
			$data["5"] = $row["5"];
			$data["6"] = "<button type='button' class='btn btn-mini btn-success' style='width:65px;' onclick=\"window.location.href='index.php?page=user&act=detail&id=".$row["0"]."'\"><i class='icon-file icon-white'></i> DETAIL</button>";
		    $response["aaData"][] = $data;
		    $index++;
		}
		
		print($uri->json(json_encode($response)));
	} elseif ($page == "user" and $act == "insert") {
		$name = strtoupper($secure->sanitize($_POST["name"]));
		$email = strtoupper($secure->sanitize($_POST["email"]));
		$password = md5(strtoupper($secure->sanitize($_POST["password"])));
		$privilege = $secure->sanitize($_POST["privilege"]);
		$active = $secure->sanitize($_POST["active"]);
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		$query = $mysqli->query("select t_user.id as id from t_user where t_user.email = '".$email."'");
		$row = $query->num_rows;
		
		if ($row <= 0) {
			$insert = $mysqli->query("insert into t_user (name,
			  								 			  email,
											 			  password,
			  								 			  privilege,
			  								 			  active,
			                                 			  user,
											 			  modified) 
				                    			  values ('".$name."',
									 					  '".$email."',
														  '".$password."',
			                                			  '".$privilege."',
			                                			  '".$active."',
														  '".$user."',
			                                			  '".$modified."')");
			
			if (!$insert) {
				$_SESSION["user"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
				header("location:../../index.php?page=".$page."&act=new");
			} else {
				$id = $mysqli->insert_id;
				$_SESSION["user"] = "USER WITH E-MAIL <b>".$email."</b> HAS BEEN SUCCESSFULLY ADDED !";
				header("location:../../index.php?page=".$page."&act=detail&id=".$id);
			}
		} else {
			$_SESSION["user"] = "USER WITH E-MAIL <b>".$email."</b> IS ALREADY REGISTERED !";
			header("location:../../index.php?page=".$page."&act=new");
		}
	} elseif ($page == "user" and $act == "update") {
		$id = $secure->sanitize($_POST["id"]);
		$name = strtoupper($secure->sanitize($_POST["name"]));
		$ori_email = strtoupper($secure->sanitize($_POST["ori_email"]));
		$email = strtoupper($secure->sanitize($_POST["email"]));
		$password = md5(strtoupper($secure->sanitize($_POST["password"])));
		$privilege = $secure->sanitize($_POST["privilege"]);
		$active = $secure->sanitize($_POST["active"]);
		$user = $_SESSION["user_id"];
		$modified = $datetime->server_datetime();
		
		if ($ori_email != $email) {
			$query = $mysqli->query("select t_user.id as id from t_user where t_user.email = '".$email."'");
			$row = $query->num_rows;
			
			if ($row <= 0) {
				if (empty($_POST["password"])) {
					$update = $mysqli->query("update t_user set name = '".$name."',
																email = '".$email."',
												   				privilege = '".$privilege."',
				                                   				active = '".$active."',
												   				user = '".$user."',
												   				modified = '".$modified."'
				                             			  where id = '".$id."'");
				} else {
				  	$update = $mysqli->query("update t_user set name = '".$name."',
				  												email = '".$email."',
												   				password = '".$password."',
												   				privilege = '".$privilege."',
				                                   				active = '".$active."',
												   				user = '".$user."',
												   				modified = '".$modified."'
				                             			  where id = '".$id."'");
				}
				
				if (!$update) {
					$_SESSION["user"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
					header("location:../../index.php?page=".$page."&act=edit&id=".$id);
				} else {
					$_SESSION["user"] = "USER WITH E-MAIL <b>".$email."</b> HAS BEEN SUCCESSFULLY UPDATED !";
					header("location:../../index.php?page=".$page."&act=detail&id=".$id);
				}
			} else {
				$_SESSION["user"] = "USER WITH E-MAIL <b>".$email."</b> IS ALREADY REGISTERED !";
				header("location:../../index.php?page=".$page."&act=edit&id=".$id);
			}
		} else {
			if (empty($_POST["password"])) {
				$update = $mysqli->query("update t_user set name = '".$name."',
															email = '".$email."',
											   				privilege = '".$privilege."',
			                                   				active = '".$active."',
											   				user = '".$user."',
											   				modified = '".$modified."'
			                             			  where id = '".$id."'");
			} else {
			  	$update = $mysqli->query("update t_user set name = '".$name."',
			  												email = '".$email."',
											   				password = '".$password."',
											   				privilege = '".$privilege."',
			                                   				active = '".$active."',
											   				user = '".$user."',
											   				modified = '".$modified."'
			                             			  where id = '".$id."'");
			}
			
			if (!$update) {
				$_SESSION["user"] = "ERROR OCCURED WHILE SUBMITTING DATA !";
				header("location:../../index.php?page=".$page."&act=edit&id=".$id);
			} else {
				$_SESSION["user"] = "USER WITH E-MAIL <b>".$email."</b> HAS BEEN SUCCESSFULLY UPDATED !";
				header("location:../../index.php?page=".$page."&act=detail&id=".$id);
			}
		}
	}
}
?>