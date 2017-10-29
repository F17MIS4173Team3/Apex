<?php

function get_activity_type_list() {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	//$db->orderBy("lastname","asc");
	$activities = $db->get("activity_types",null,array("id","name","input_type","points"));
	if (!$activities) {
		// User does not exist
		return -1;
	}
	else {
		/*$userlist = array();
		if ($include_blank) {
			array_push($userlist, array("id"=>"","name"=>""));
		}
		foreach($userinfo as $row) {
			$fullname = $row["lastname"] . ", " . $row["firstname"];
			$userid = $row["id"];
			array_push($userlist, array("id"=>$userid,"name"=>$fullname));
		}*/
		return $activities;
	}
}

function approve_request($actid,$empid) {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$data = array ('approver' => $empid);
	$db->where('id',$actid);
	if ($db->update ('activities', $data)) {
		return TRUE;
	}
	else {
		return FALSE;
	}
}

function get_unapproved_list() {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->join("activity_types t", "t.id=a.activityid","LEFT");
	$db->where('a.approver', NULL, 'IS');
	$db->join("employee e", "a.employeeid=e.id","LEFT");
	$db->orderBy("a.activitydate","asc");
	$activities = $db->get("activities a",null,"a.id,t.name AS aname,a.activitydate,t.input_type,e.username,a.points,a.input,a.duration,CONCAT(e.lastname,\", \",e.firstname) AS ename");
	return $activities;
}

function get_emp_activity_history($empid) {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->join("activity_types t", "t.id=a.activityid","LEFT");
	$db->where('a.employeeid',$empid);
	$db->orderBy("a.activitydate","asc");
	$activities = $db->get("activities a",null,"t.name,a.activitydate,a.points,a.input,a.duration,a.approver");
	return $activities;
}

function get_activity_type_note($id) {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->where('id',$id);
	$activities = $db->getOne("activity_types","note");
	if (!$activities) {
		// User does not exist
		return -1;
	}
	else {
		return $activities['note'];
	}
}

function submit_activity($empid,$actid,$acttype,$actdate,$input = "") {
	$actid = ltrim($actid, '0');
	$inserted = FALSE;

	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->where('id',$actid);
	$act_type = $db->getOne("activity_types");
	
	// Test for submitter, empid = submitter including self
	if ($_COOKIE["wellness_login_id"] != $empid) {
		$submitter = $_COOKIE["wellness_login_id"]; //submitter is self
	}
	else {
		$submitter = $empid;
	}
	
	// Check if approval required
	$approved = $submitter;
	if ($act_type["approval"] == 0) { // not required
		$sendapproval = FALSE;
		$approver = $submitter;
	}
	else { // is required
		$sendapproval = TRUE;
		$approver = NULL;
	}

	// Check if eligible on per
	if ($act_type["maxmonth"]) { // per month
		if ($act_type["maxmonth"] == 0) { //no limit
			$maxeligible = TRUE;
		}
		else { //limited, check to see how many
			$activities = $db->rawQuery("SELECT COUNT(*) AS total FROM activities WHERE employeeid = " . $empid . " AND activityid = " . $actid . " AND MONTH(activitydate) = MONTH(CURRENT_DATE()) AND YEAR(activitydate) = YEAR(CURRENT_DATE())");
			debug($activities[0]["total"] . " < " . $act_type["maxmonth"]);
			if ($activities[0]["total"] < $act_type["maxmonth"]) {
				$maxeligible = TRUE;
			}
			else {
				$maxeligible = FALSE;
				$error = "You have submitted the maximum entries for the month.";
			}
		}
	}
	elseif ($act_type["maxyear"]) { // per year
		if ($act_type["maxyear"] == 0) { //no limit
			$maxeligible = TRUE;
		}
		else { //limited, check to see how many
			$activities = $db->rawQuery("SELECT COUNT(*) AS total FROM activities WHERE employeeid = " . $empid . " AND activityid = " . $actid . " AND YEAR(activitydate) = YEAR(CURRENT_DATE())");
			debug($activities[0]["total"] . " < " . $act_type["maxyear"]);
			if ($activities[0]["total"] < $act_type["maxyear"]) {
				$maxeligible = TRUE;
			}
			else {
				$maxeligible = FALSE;
				$error = "You have submitted the maximum entries for the year.";
			}
		}		
	}
	else {
		$maxeligible = TRUE;
	}

	// Last step based on type
	if ($maxeligible) {
		if ($acttype == "n") {
			$points = $act_type["points"];
			$data = array("employeeid" => $empid,
				"activityid" => $actid,
				"activitydate" => date("Y-m-d",strtotime($actdate)),
				"approver" => $approver,
				"submitter" => $submitter,
				"points" => $points
			);
			$returnid = $db->insert ('activities', $data);
			if ($returnid) {
				$inserted = TRUE;
			}
			else {
				$inserted = FALSE;
				$error = "There was an error with the query.";
			}
		}
		if ($acttype == "f") {
			$points = $act_type["points"];
			$data = array("employeeid" => $empid,
				"activityid" => $actid,
				"activitydate" => date("Y-m-d",strtotime($actdate)),
				"input" => $input,
				"approver" => $approver,
				"submitter" => $submitter,
				"points" => $points
			);
			$returnid = $db->insert ('activities', $data);
			if ($returnid) {
				$inserted = TRUE;
			}
			else {
				$inserted = FALSE;
				$error = "There was an error with the query.";
			}
		}
		if ($acttype == "l") {
			$points = $act_type["points"];
			$data = array("employeeid" => $empid,
				"activityid" => $actid,
				"activitydate" => date("Y-m-d",strtotime($actdate)),
				"input" => $input,
				"approver" => $approver,
				"submitter" => $submitter,
				"points" => $points
			);
			$returnid = $db->insert ('activities', $data);
			if ($returnid) {
				$inserted = TRUE;
			}
			else {
				$inserted = FALSE;
				$error = "There was an error with the query.";
			}
		}
		elseif ($acttype == "t") {
			if ($act_type["id"] == 4) { // Check if there is a total of 4 entries already
				$query = "SELECT COUNT(*) AS total FROM activities WHERE employeeid = " . $empid . " AND activityid = " . $actid . " AND MONTH(activitydate) = MONTH('" . date("Y-m-d",strtotime($actdate)) . "') AND YEAR(activitydate) = YEAR('" . date("Y-m-d",strtotime($actdate)) . "')";
				//debug($query);
				$activities = $db->rawQuery($query);
				print_r($activities);
				if ($activities[0]["total"] != 3) {
					$points = 0;
				}
				elseif ($activities[0]["total"] == 3) {
					$points = $act_type["points"];
				}
			}
			else {
				$points = $act_type["points"];
			}
			$data = array("employeeid" => $empid,
				"activityid" => $actid,
				"activitydate" => date("Y-m-d",strtotime($actdate)),
				"duration" => $input,
				"approver" => $approver,
				"submitter" => $submitter,
				"points" => $points
			);
			$returnid = $db->insert ('activities', $data);
			if ($returnid) {
				$inserted = TRUE;
			}
			else {
				$inserted = FALSE;
				$error = "There was an error with the query.";
			}
		}
	}
	
	if ($inserted) {
		// returns response, id, approval(0,1), points, error
		return array("response" => $inserted,
			"id" => $returnid,
			"approval" => $sendapproval,
			"points" => $points
		);
	}
	else {
		return array("response" => $inserted, "error" => $error);
	}
}

function get_yearly_total($empid,$approved,$year = NULL) {
	if (!$year) {
		$year = date("Y");
	}
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ($approved == TRUE) {
		$approvedsql = " AND approver > 0";
	}
	$activities = $db->rawQuery("SELECT SUM(points) AS total FROM activities WHERE employeeid = " . $empid . $approvedsql . " AND YEAR(activitydate) = " . $year);
	if ($activities[0]["total"]) {
		return $activities[0]["total"];
	}
	else {
		return "0";
	}
}

function get_activity_type_options($actid) {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->where('id',$actid);
	$activity_type = $db->getOne("activity_types","options");
	$options = explode("|",$activity_type["options"]);
	return $options;
}

?>