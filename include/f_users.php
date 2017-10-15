<?php


function check_login($username,$password) {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->where('username',$username);
	$userinfo = $db->getOne("employee");
	if (!$userinfo) {
		// User does not exist
		return 0;
	}
	elseif ($password != $userinfo['password']) {
		// Password is incorrect
		return -1;
	}
	elseif ($password == $userinfo['password']) {
		// Password is correct
		return 1;
	}
	else {
		// Unknown error_get_last
	}
}

function get_login_id($username) {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->where('username',$username);
	$userinfo = $db->getOne("employee");
	if (!$userinfo) {
		// User does not exist
		return -1;
	}
	else {
		return $userinfo['id'];
	}
}

function get_fullname($id) {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->where('id',$id);
	$userinfo = $db->getOne("employee");
	if (!$userinfo) {
		// User does not exist
		return -1;
	}
	else {
		$fullname = $userinfo['firstname'] . " " . $userinfo['lastname'];
		return $fullname;
	}
}

function get_user_level($id) {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->where('id',$id);
	$userinfo = $db->getOne("employee");
	if (!$userinfo) {
		// User does not exist
		return -1;
	}
	else {
		return $userinfo['userlevel'];
	}
}

function get_user_data($id) {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->where('id',$id);
	$userinfo = $db->getOne("employee");
	if (!$userinfo) {
		// User does not exist
		return -1;
	}
	else {
		return $userinfo;
	}
}

function get_user_list($include_blank = false) {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->orderBy("lastname","asc");
	$userinfo = $db->get("employee",null,array("id","lastname","firstname"));
	if (!$userinfo) {
		// User does not exist
		return -1;
	}
	else {
		$userlist = array();
		if ($include_blank) {
			array_push($userlist, array("id"=>"","name"=>""));
		}
		foreach($userinfo as $row) {
			$fullname = $row["lastname"] . ", " . $row["firstname"];
			$userid = $row["id"];
			array_push($userlist, array("id"=>$userid,"name"=>$fullname));
		}
		return $userlist;
	}
}

?>