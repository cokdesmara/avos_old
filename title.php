<?php
if(!defined("RESTRICTED")) exit("NO DIRECT SCRIPT ACCESS ALLOWED !");

switch($_GET["page"]) {
	case "dashboard":
		if (!empty($_SESSION["user_session"]))
			echo "DASHBOARD";
		else
			echo "LOGIN";
	break;
	
	case "account":
		if (!empty($_SESSION["user_session"])) {
			if ($_GET["act"] == "") {
				echo "ACCOUNT MANAGEMENT (DETAIL)";
			} elseif ($_GET["act"] == "edit") {
				echo "ACCOUNT MANAGEMENT (EDIT)";
			} else {
				echo "ERROR (NOT FOUND)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "user":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR") {
				if ($_GET["act"] == "") {
					echo "USER REFERENCE (DATA)";
				} elseif ($_GET["act"] == "new") {
					echo "USER REFERENCE (NEW)";
				} elseif ($_GET["act"] == "edit") {
					echo "USER REFERENCE (EDIT)";
				} elseif ($_GET["act"] == "detail") {
					echo "USER REFERENCE (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "aviation":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				if ($_GET["act"] == "") {
					echo "AVIATION REFERENCE (DATA)";
				} elseif ($_GET["act"] == "new") {
					echo "AVIATION REFERENCE (NEW)";
				} elseif ($_GET["act"] == "edit") {
					echo "AVIATION REFERENCE (EDIT)";
				} elseif ($_GET["act"] == "detail") {
					echo "AVIATION REFERENCE (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "aircraft":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				if ($_GET["act"] == "") {
					echo "AIRCRAFT REFERENCE (DATA)";
				} elseif ($_GET["act"] == "new") {
					echo "AIRCRAFT REFERENCE (NEW)";
				} elseif ($_GET["act"] == "edit") {
					echo "AIRCRAFT REFERENCE (EDIT)";
				} elseif ($_GET["act"] == "detail") {
					echo "AIRCRAFT REFERENCE (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "airline":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				if ($_GET["act"] == "") {
					echo "AIRLINE REFERENCE (DATA)";
				} elseif ($_GET["act"] == "new") {
					echo "AIRLINE REFERENCE (NEW)";
				} elseif ($_GET["act"] == "edit") {
					echo "AIRLINE REFERENCE (EDIT)";
				} elseif ($_GET["act"] == "detail") {
					echo "AIRLINE REFERENCE (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "equipment":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				if ($_GET["act"] == "") {
					echo "EQUIPMENT REFERENCE (DATA)";
				} elseif ($_GET["act"] == "new") {
					echo "EQUIPMENT REFERENCE (NEW)";
				} elseif ($_GET["act"] == "edit") {
					echo "EQUIPMENT REFERENCE (EDIT)";
				} elseif ($_GET["act"] == "detail") {
					echo "EQUIPMENT REFERENCE (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "flight":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				if ($_GET["act"] == "") {
					echo "FLIGHT REFERENCE (DATA)";
				} elseif ($_GET["act"] == "new") {
					echo "FLIGHT REFERENCE (NEW)";
				} elseif ($_GET["act"] == "edit") {
					echo "FLIGHT REFERENCE (EDIT)";
				} elseif ($_GET["act"] == "detail") {
					echo "FLIGHT REFERENCE (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "config":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				if ($_GET["act"] == "") {
					echo "CONFIG REFERENCE (DATA)";
				} elseif ($_GET["act"] == "new") {
					echo "CONFIG REFERENCE (NEW)";
				} elseif ($_GET["act"] == "edit") {
					echo "CONFIG REFERENCE (EDIT)";
				} elseif ($_GET["act"] == "detail") {
					echo "CONFIG REFERENCE (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "status":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER") {
				if ($_GET["act"] == "") {
					echo "STATUS REFERENCE (DATA)";
				} elseif ($_GET["act"] == "new") {
					echo "STATUS REFERENCE (NEW)";
				} elseif ($_GET["act"] == "edit") {
					echo "STATUS REFERENCE (EDIT)";
				} elseif ($_GET["act"] == "detail") {
					echo "STATUS REFERENCE (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "mealorderpob":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER" or $_SESSION["user_privilege"] == "MONITORING") {
				if ($_GET["act"] == "") {
					echo "MEAL ORDER & P.O.B ENTRY (DATA)";
				} elseif ($_GET["act"] == "new") {
					echo "MEAL ORDER & P.O.B ENTRY (NEW)";
				} elseif ($_GET["act"] == "edit") {
					echo "MEAL ORDER & P.O.B ENTRY (EDIT)";
				} elseif ($_GET["act"] == "detail") {
					echo "MEAL ORDER & P.O.B ENTRY (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "production":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "PRODUCTION" or $_SESSION["user_privilege"] == "MONITORING") {
				if ($_GET["act"] == "") {
					echo "PRODUCTION ENTRY (DATA)";
				} elseif ($_GET["act"] == "edit") {
					echo "PRODUCTION ENTRY (EDIT)";
				} elseif ($_GET["act"] == "detail") {
					echo "PRODUCTION ENTRY (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "mealuplift":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "OPERATION GA" or $_SESSION["user_privilege"] == "OPERATION NONGA" or $_SESSION["user_privilege"] == "MONITORING") {
				if ($_GET["act"] == "") {
					echo "MEAL UPLIFT ENTRY (DATA)";
				} elseif ($_GET["act"] == "edit") {
					echo "MEAL UPLIFT ENTRY (EDIT)";
				} elseif ($_GET["act"] == "detail") {
					echo "MEAL UPLIFT ENTRY (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "flightmeal":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "MONITORING") {
				if ($_GET["act"] == "") {
					echo "FLIGHT MEAL REVIEW (DATA)";
				} elseif ($_GET["act"] == "detail") {
					echo "FLIGHT MEAL REVIEW (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "overcost":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "MONITORING") {
				if ($_GET["act"] == "") {
					echo "OVERCOST REVIEW (DATA)";
				} elseif ($_GET["act"] == "detail") {
					echo "OVERCOST REVIEW (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "loadfactor":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER" or $_SESSION["user_privilege"] == "MONITORING") {
				if ($_GET["act"] == "") {
					echo "LOAD FACTOR REVIEW (DATA)";
				} elseif ($_GET["act"] == "detail") {
					echo "LOAD FACTOR REVIEW (DETAIL)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "report":
		if (!empty($_SESSION["user_session"])) {
			if ($_GET["act"] == "mealorderpob") {
				if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER" or $_SESSION["user_privilege"] == "MONITORING") {
					echo "MEAL ORDER & P.O.B REPORT (SUMMARY)";
				} else {
					echo "ERROR (NOT AUTHORIZED)";
				}
			} elseif ($_GET["act"] == "production") {
				if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "PRODUCTION" or $_SESSION["user_privilege"] == "MONITORING") {
					echo "PRODUCTION REPORT (SUMMARY)";
				} else {
					echo "ERROR (NOT AUTHORIZED)";
				}
			} elseif ($_GET["act"] == "mealuplift") {
				if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "OPERATION GA" or $_SESSION["user_privilege"] == "OPERATION NONGA" or $_SESSION["user_privilege"] == "MONITORING") {
					echo "MEAL UPLIFT REPORT (SUMMARY)";
				} else {
					echo "ERROR (NOT AUTHORIZED)";
				}
			} elseif ($_GET["act"] == "oversupply") {
				if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "MONITORING") {
					echo "OVER SUPPLY REPORT (SUMMARY)";
				} else {
					echo "ERROR (NOT AUTHORIZED)";
				}
			} elseif ($_GET["act"] == "overproduction") {
				if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "MONITORING") {
					echo "OVER PRODUCTION REPORT (SUMMARY)";
				} else {
					echo "ERROR (NOT AUTHORIZED)";
				}
			} elseif ($_GET["act"] == "wastedmeal") {
				if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER" or $_SESSION["user_privilege"] == "MONITORING") {
					echo "WASTED MEAL REPORT (SUMMARY)";
				} else {
					echo "ERROR (NOT AUTHORIZED)";
				}
			} elseif ($_GET["act"] == "overcost") {
				if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "MONITORING") {
					echo "OVERCOST REPORT (SUMMARY)";
				} else {
					echo "ERROR (NOT AUTHORIZED)";
				}
			} elseif ($_GET["act"] == "loadfactor") {
				if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "ORDER CENTER" or $_SESSION["user_privilege"] == "MONITORING") {
					echo "LOAD FACTOR REPORT (SUMMARY)";
				} else {
					echo "ERROR (NOT AUTHORIZED)";
				}
			} else {
				echo "ERROR (NOT FOUND)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "profile":
		if (!empty($_SESSION["user_session"])) {
			if ($_GET["act"] == "") {
					echo "COMPANY PROFILE (DETAIL)";
			} elseif ($_GET["act"] == "edit") {
				if ($_SESSION["user_privilege"] == "ADMINISTRATOR") {
					echo "COMPANY PROFILE (EDIT)";
				} else {
					echo "ERROR (NOT AUTHORIZED)";
				}
			} else {
				echo "ERROR (NOT FOUND)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "log":
		if (!empty($_SESSION["user_session"])) {
			if ($_SESSION["user_privilege"] == "ADMINISTRATOR" or $_SESSION["user_privilege"] == "MONITORING") {
				if ($_GET["act"] == "") {
					echo "USER LOG (DATA)";
				} else {
					echo "ERROR (NOT FOUND)";
				}
			} else {
				echo "ERROR (NOT AUTHORIZED)";
			}
		} else {
			echo "LOGIN";
		}
	break;
	
	case "":
		if (!empty($_SESSION["user_session"]))
			echo "DASHBOARD";
		else
			echo "LOGIN";
	break;
	
	default:
		if (!empty($_SESSION["user_session"]))
			echo "ERROR (NOT FOUND)";
		else
			echo "LOGIN";
	break;
}
?>