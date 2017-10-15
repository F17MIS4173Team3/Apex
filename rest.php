<?php
include "include/globals.php";
include "include/f_users.php";

if (!isset($_COOKIE["wellness_login"])) {
	// User is not logged in
	header('Location: login.php');
}
else {
	setcookie("wellness_login",$_COOKIE["wellness_login"],time()+3600);
	if (isset($_REQUEST["userdata"])) {
		$userdata = json_encode(get_user_data($_REQUEST["userdata"]));
		echo $userdata;
	}
}
?>