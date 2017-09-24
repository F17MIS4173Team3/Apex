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

?>