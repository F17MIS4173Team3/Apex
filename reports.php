<?php
include "include/globals.php";
include "include/f_users.php";

if (!isset($_COOKIE["wellness_login"])) {
	// User is not logged in
	header('Location: login.php');
}
else {
	setcookie("wellness_login",$_COOKIE["wellness_login"],time()+3600);
	setcookie("wellness_login_id",$_COOKIE["wellness_login_id"],time()+3600);
	get_header_text("reports","Reports",get_fullname($_COOKIE["wellness_login_id"]));
	if (get_user_level($_COOKIE["wellness_login_id"]) != 1) {
?>
<p>You must be an admin to access this page.</p>
<?php
	} else {
?>

<h1>Reports</h1>

<?php
	}
	get_footer_text();
}
?>