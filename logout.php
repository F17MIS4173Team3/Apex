<?php
include "include/globals.php";

if (!isset($_COOKIE["wellness_login"])) {
	// User is not logged in
	header('Location: login.php');
}
else {
	setcookie("wellness_login","",time()-3600);
	setcookie("wellness_login_id","",time()-3600);
	header('Location: login.php');
}
?>