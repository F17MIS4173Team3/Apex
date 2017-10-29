<?php
include "include/globals.php";
include "include/f_users.php";

if (isset($_COOKIE["wellness_login"])) {
	// User is already logged in
	header('Location: index.php');
}
else {
	if (isset($_REQUEST["username"]) && isset($_REQUEST["password"])) {
		// Test username and password
		$username = $_REQUEST["username"];
		$password = md5($_REQUEST["password"]);
		
		if ($username && $password) {
			$login = check_login($username,$password);
			if ($login["response"] == TRUE) {
				$uniqid = uniqid();
				setcookie("wellness_login",$uniqid,time()+3600);
				setcookie("wellness_login_id",get_login_id($username),time()+3600);
				header('Location: index.php');
			}
			else {
				$error = '<div class="error">'.$login["error"].'</div>';
			}
		}
		elseif ($username == "" || $password = "") {
			$error = '<div class="error">You must enter a username and password.</div>';
		}
	}
	get_header_text("login","Login","");

	if (isset($error)) {
		echo '<div class="error" align="center">'.$error.'</div>';
	}

?>

<div id="loginbox">
	<form method="post" action="login.php">
	Username:<br /><input type="text" name="username" size="30"><br /><br />
	Password:<br /><input type="password" name="password" size="30"><br /><br />
	<input class="submit" type="submit" value="Login">
	</form>
</div>

<?php
	get_footer_text();
}
?>