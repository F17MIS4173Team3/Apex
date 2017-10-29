<?php


function check_login($username,$password) {
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->where('username',$username);
	$userinfo = $db->getOne("employee","password,active");
	if (!$userinfo) {
		// User does not exist
		return array("response"=>FALSE,"error"=>"User ".$username." does not exist.");
	}
	elseif ($userinfo["active"] == FALSE) {
		return array("response"=>FALSE,"error"=>"The user is not active, contact the HR office.");
	}
	elseif ($password != $userinfo['password']) {
		// Password is incorrect
		return array("response"=>FALSE,"error"=>"Password is incorrect.");
	}
	elseif ($password == $userinfo['password']) {
		// Password is correct
		return array("response"=>TRUE);
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

function update_user($empid,$data) {
	$update_data = array(
		"firstname" => $data["firstname"],
		"lastname" => $data["lastname"],
		"email" => $data["email"],
		"userlevel" => $data["userlevel"],
		"active" => $data["active"]
	);
	if ($data["password"] != "") {
		$update_data["password"] = md5($data["password"]);
	}
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->where('id',$empid);
	if ($db->update ('employee', $update_data)) {
		return array("response"=>TRUE);
	}
	else {
		return array("response"=>FALSE,"error"=>$db->getLastError());
	}

}

function add_user($data) {
	$insert_data = array(
		"username" => $data["username"],
		"firstname" => $data["firstname"],
		"lastname" => $data["lastname"],
		"email" => $data["email"],
		"userlevel" => $data["userlevel"],
		"password" => md5($data["password"]),
		"active" => $data["active"]
	);
	$db = new MysqliDb (DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db->where('username',$data["username"]);
	$user = $db->getOne("employee","id");
	debug($user["id"]);
	if ($user["id"]) {
		return array("response"=>FALSE,"error"=>"This user already exists.");
	}
	elseif ($db->insert ('employee', $insert_data)) {
		return array("response"=>TRUE);
	}
	else {
		return array("response"=>FALSE,"error"=>$db->getLastError());
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